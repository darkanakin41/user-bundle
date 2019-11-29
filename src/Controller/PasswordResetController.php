<?php

namespace Darkanakin41\UserBundle\Controller;

use Darkanakin41\UserBundle\Entity\User;
use Darkanakin41\UserBundle\Form\PasswordResetForm;
use Darkanakin41\UserBundle\Form\PasswordResetRequestForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteController
 * @package Darkanakin41\UserBundle\Controller
 * @Route("/password-reset/")
 */
class PasswordResetController extends Controller
{
    const TOKEN_TYPE = "reset_password";

    /**
     * @Route("request.html", name="darkanakin41_user_password_reset_request")
     * @return Response
     */
    public function request(Request $request, $error = ""): Response
    {
        if (!is_null($this->getUser())) return $this->redirectToRoute("front");
        $form = $this->createForm(PasswordResetRequestForm::class, NULL, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_password_reset_request_confirm"),
        ]);
        $form->handleRequest($request);
        if (!empty($error)) {
            $form->addError(new FormError($error));
        }
        return $this->render('@Darkanakin41User/PasswordReset/request.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("request-confirm.html", name="darkanakin41_user_password_reset_request_confirm")
     * @return Response
     */
    public function requestConfirm(Request $request): Response
    {
        if (!is_null($this->getUser())) return $this->redirectToRoute("front");
        $form = $this->createForm(PasswordResetRequestForm::class, NULL, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_profile_delete_confirm"),
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->request($request);
        }

        $user = $this->get("doctrine")->getRepository(User::class)->findOneBy(array("username" => $form->get("username")->getData()));
        if (is_null($user)) {
            $user = $this->get("doctrine")->getRepository(User::class)->findOneBy(array("email" => $form->get("username")->getData()));
        }
        if (is_null($user)) {
            return $this->request($request, "error.unknown_user");
        }

        if (!is_null($user->getToken()) && $user->getTokenType() == self::TOKEN_TYPE) {
            return $this->render('@Darkanakin41User/error.html.twig', [
                'code' => "error.reset_password_pending",
            ]);
        }

        $user->generateToken(self::TOKEN_TYPE);

        $message = new \Swift_Message($this->get("translator")->trans("mail.reset_password.subject", [], "Darkanakin41User"));
        $message->setFrom("scoopturnfrance@gmail.com", "Scoop Turn");
        $message->setTo($user->getEmail());
        $message->setBody($this->renderView("@Darkanakin41User/Mail/reset_password.html.twig", ["user" => $user]), 'text/html');
        $this->container->get("mailer")->send($message);

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        return $this->render('@Darkanakin41User/PasswordReset/request.confirm.html.twig');
    }

    /**
     * @Route("reset-{token}.html", name="darkanakin41_user_password_reset_form")
     * @return Response
     */
    public function form(Request $request, $token): Response
    {
        $user = $this->container->get("doctrine")->getRepository(User::class)->findOneBy(array("token_type" => self::TOKEN_TYPE, "token" => $token));
        if (is_null($user) || !$user instanceof User) {
            return $this->render('@Darkanakin41User/error.html.twig', [
                'code' => "error.token_invalid",
            ]);
        }

        $form = $this->createForm(PasswordResetForm::class, NULL, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_password_reset_confirm", ['token' => $token]),
        ]);
        $form->handleRequest($request);

        return $this->render('@Darkanakin41User/PasswordReset/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("confirm-{token}.html", name="darkanakin41_user_password_reset_confirm")
     * @return Response
     */
    public function formConfirm(Request $request, $token): Response
    {
        $user = $this->container->get("doctrine")->getRepository(User::class)->findOneBy(array("token_type" => self::TOKEN_TYPE, "token" => $token));
        if (is_null($user) || !$user instanceof User) {
            return $this->render('@Darkanakin41User/error.html.twig', [
                'code' => "error.token_invalid",
            ]);
        }

        $form = $this->createForm(PasswordResetForm::class, NULL, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_password_reset_confirm", ['token' => $token]),
        ]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->form($request, $token);
        }

        $password = $this->get("security.password_encoder")->encodePassword($user, $form->get("password")->getData());
        $user->setPassword($password);

        $user->setToken(NULL);
        $user->setTokenDate(NULL);
        $user->setTokenType(NULL);

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        return $this->render('@Darkanakin41User/PasswordReset/reset.confirm.html.twig');
    }
}

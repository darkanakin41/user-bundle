<?php

namespace PLejeune\UserBundle\Controller;


use PLejeune\UserBundle\Entity\User;
use PLejeune\UserBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/registration")
 */
class RegistrationController extends Controller
{
    const TOKEN_TYPE = "registration";
    /**
     * @Route(".html", name="plejeune_user_registration")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user, [
            'action' => $this->generateUrl('plejeune_user_registration_check'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        return $this->render('@PLejeuneUser/Registration/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("-check.html", name="plejeune_user_registration_check")
     * @Method({"POST"})
     */
    public function check(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user, [
            'action' => $this->generateUrl('plejeune_user_registration_check'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->index($request);
        }

        $password = $this->get("security.password_encoder")->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $user->generateToken(self::TOKEN_TYPE);

        $message = new \Swift_Message($this->get("translator")->trans("mail.registration.subject",[], "PLejeuneUser"));
        $message->setFrom("scoopturnfrance@gmail.com", "Scoop Turn");
        $message->setTo($user->getEmail());
        $message->setBody($this->renderView("@PLejeuneUser/Mail/registration.html.twig", ["user" => $user]), 'text/html');
        $this->container->get("mailer")->send($message);

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        return $this->render('@PLejeuneUser/Registration/confirmation.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("-validate/{token}.html", name="plejeune_user_registration_validate")
     */
    public function validate($token): Response
    {
        $user = $this->container->get("doctrine")->getRepository(User::class)->findOneBy(array("token_type" => self::TOKEN_TYPE, "token" => $token));
        if(is_null($user) || !$user instanceof User){
            return $this->render('@PLejeuneUser/error.html.twig', [
                'code' => "error.token_invalid",
            ]);
        }
        if($user->getEnable() && !is_null($user->getDateValidation())){
            return $this->redirectToRoute("plejeune_user_login");
        }

        $user->setToken(null);
        $user->setTokenDate(null);
        $user->setTokenType(null);

        $user->setEnable(true);
        $user->setDateValidation(new \DateTime("now"));

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        return $this->render('@PLejeuneUser/Registration/validation.html.twig', [
            'user' => $user,
        ]);
    }
}
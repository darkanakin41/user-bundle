<?php

namespace Darkanakin41\UserBundle\Controller;

use Darkanakin41\UserBundle\Form\PasswordForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfilController
 * @package Darkanakin41\UserBundle\Controller
 * @Route("/profile/password/")
 */
class PasswordController extends Controller
{
    /**
     * @Route("edit.html", name="darkanakin41_user_password_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $error = ""): Response
    {
        if (is_null($this->getUser())) return $this->redirectToRoute("darkanakin41_user_login");
        $form = $this->createForm(PasswordForm::class, NULL, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_password_update"),
        ]);
        $form->handleRequest($request);
        if (!empty($error)) {
            $form->addError(new FormError($error));
        }
        return $this->render('@Darkanakin41User/Password/edit.html.twig', ['user' => $this->getUser(), 'form' => $form->createView()]);
    }

    /**
     * @Route("update.html", name="darkanakin41_user_password_update")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request): Response
    {
        $user = $this->getUser();
        if (is_null($user)) return $this->redirectToRoute("darkanakin41_user_login");
        $form = $this->createForm(PasswordForm::class, null, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_profile_update"),
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->edit($request);
        }

        if (!$this->container->get("security.password_encoder")->isPasswordValid($user, $form->get("old_password")->getData())) {
            return $this->edit($request, "form.profil.wrong_password");
        }

        $password = $this->get("security.password_encoder")->encodePassword($user, $form->get("password")->getData());
        $user->setPassword($password);

        $user->setToken(null);
        $user->setTokenDate(null);
        $user->setTokenType(null);

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        return $this->redirectToRoute('darkanakin41_user_profile');
    }

}

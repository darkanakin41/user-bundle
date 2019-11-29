<?php

namespace Darkanakin41\UserBundle\Controller;

use Darkanakin41\UserBundle\Form\ProfilForm;
use Darkanakin41\UserBundle\Sanitizer\SocialSanitizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @TODO rendre paramétrable le template de base
 * @TODO rendre paramétrable le template de mail
 */

/**
 * Class ProfilController
 * @package Darkanakin41\UserBundle\Controller
 * @Route("/profile")
 */
class ProfilController extends Controller
{
    /**
     * @Route(".html", name="darkanakin41_user_profile")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        if (is_null($this->getUser())) return $this->redirectToRoute("darkanakin41_user_login");
        return $this->render('@Darkanakin41User/Profile/profile.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @Route("/edit.html", name="darkanakin41_user_profile_edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $error = ""): Response
    {
        if (is_null($this->getUser())) return $this->redirectToRoute("darkanakin41_user_login");
        $form = $this->createForm(ProfilForm::class, $this->getUser(), [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_profile_update"),
        ]);
        $form->handleRequest($request);
        if (!empty($error)) {
            $form->addError(new FormError($error));
        }
        return $this->render('@Darkanakin41User/Profile/edit.html.twig', ['user' => $this->getUser(), 'form' => $form->createView()]);
    }

    /**
     * @Route("/update.html", name="darkanakin41_user_profile_update")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function update(Request $request): Response
    {
        $user = $this->getUser();
        if (is_null($user)) return $this->redirectToRoute("darkanakin41_user_login");
        $form = $this->createForm(ProfilForm::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl("darkanakin41_user_profile_update"),
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->edit($request);
        }

        if (!$this->container->get("security.password_encoder")->isPasswordValid($user, $form->get("password")->getData())) {
            return $this->edit($request, "form.profil.wrong_password");
        }

        if (!is_null($form->get("avatarFile")->getData())) {
            $folder = sprintf("user/%d-%s/", $user->getId(), $this->get("darkanakin41.media.slugify")->slugify($user->getUsername()));
            $new_avatar = $this->get("darkanakin41.media.fileupload")->upload($form->get("avatarFile")->getData(), "avatar", $folder);
            $old_avatar = $user->getAvatar();
            if (!is_null($old_avatar)) @unlink($old_avatar);
            $user->setAvatar($new_avatar);
        }

        SocialSanitizer::sanitizeUser($user);

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        return $this->redirectToRoute('darkanakin41_user_profile');
    }

}

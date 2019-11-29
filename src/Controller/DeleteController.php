<?php

namespace Darkanakin41\UserBundle\Controller;

use Darkanakin41\UserBundle\Form\DeleteForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteController
 * @package Darkanakin41\UserBundle\Controller
 * @Route("/profile/delete")
 */
class DeleteController extends Controller
{
    /**
     * @Route(".html", name="darkanakin41_user_profile_delete")
     * @return Response
     */
    public function index(Request $request): Response
    {
        if (is_null($this->getUser())) return $this->redirectToRoute("darkanakin41_user_login");
        $form = $this->createForm(DeleteForm::class, NULL, [
            'method' => 'DELETE',
            'action' => $this->generateUrl("darkanakin41_user_profile_delete_confirm"),
        ]);
        $form->handleRequest($request);
        return $this->render('@Darkanakin41User/Delete/form.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("-confirm.html", name="darkanakin41_user_profile_delete_confirm")
     * @return Response
     */
    public function confirm(Request $request): Response
    {
        if (is_null($this->getUser())) return $this->redirectToRoute("darkanakin41_user_login");
        $form = $this->createForm(DeleteForm::class, NULL, [
            'method' => 'DELETE',
            'action' => $this->generateUrl("darkanakin41_user_profile_delete_confirm"),
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->index($request);
        }
        $user = $this->getUser();
        $user->setUsername("utilisateur.supprime." . $user->getId());
        $user->setFirstname("utilisateur");
        $user->setLastname("supprimé");
        $user->setDateNaissance(new \DateTime("now"));
        $user->setEmail($user->getUsername() . "@scoopturn.com");
        $user->setPassword("UTILISATEUR SUPPRIME");
        $user->setFacebook(NULL);
        $user->setTwitter(NULL);
        $user->setInstagram(NULL);
        $user->setYoutube(NULL);
        $user->setTwitch(NULL);
        $user->setEnable(FALSE);
        if(!is_null($user->getAvatar())){
            @unlink($user->getAvatar());
            $user->setAvatar(NULL);
        }

        $this->container->get("doctrine")->getManager()->persist($user);
        $this->container->get("doctrine")->getManager()->flush();

        $this->container->get("security.token_storage")->setToken(null);
        $this->container->get("request_stack")->getMasterRequest()->getSession()->invalidate();

        return $this->redirectToRoute("front");
    }
}

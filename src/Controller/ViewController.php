<?php

namespace Darkanakin41\UserBundle\Controller;

use Darkanakin41\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfilController
 * @package Darkanakin41\UserBundle\Controller
 * @Route("/user/{id}-{username}")
 */
class ViewController extends Controller
{
    /**
     * @Route(".html", name="darkanakin41_user_view")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profil($id): Response
    {
        $user = $this->container->get("doctrine")->getRepository(User::class)->find($id);
        if (is_null($user)) return $this->redirectToRoute("front");
        return $this->render('@Darkanakin41User/View/profil.html.twig', ['user' => $user]);
    }
}

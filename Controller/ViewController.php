<?php

namespace PLejeune\UserBundle\Controller;

use PLejeune\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfilController
 * @package PLejeune\UserBundle\Controller
 * @Route("/user/{id}-{username}")
 */
class ViewController extends Controller
{
    /**
     * @Route(".html", name="plejeune_user_view")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profil($id): Response
    {
        $user = $this->container->get("doctrine")->getRepository(User::class)->find($id);
        if (is_null($user)) return $this->redirectToRoute("front");
        return $this->render('@PLejeuneUser/View/profil.html.twig', ['user' => $user]);
    }
}
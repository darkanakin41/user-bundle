<?php

namespace PLejeune\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login.html", name="plejeune_user_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): Response
    {
        $helper = $this->container->get("security.authentication_utils");
        return $this->render('@PLejeuneUser/Login/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/logout.html", name="plejeune_user_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
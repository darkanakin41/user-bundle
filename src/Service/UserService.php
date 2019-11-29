<?php

namespace Darkanakin41\UserBundle\Service;


use Darkanakin41\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserService extends \Twig_Extension
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'UserService';
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('user_is_validated', array($this, 'isValidated')),
        );
    }

    public function isValidated(User $user){
        return $user->getEnable() && !is_null($user->getDateValidation());
    }

}

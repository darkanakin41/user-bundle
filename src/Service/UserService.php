<?php

namespace Darkanakin41\UserBundle\Service;


use Darkanakin41\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserService extends AbstractExtension
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
            new TwigFunction('user_is_validated', array($this, 'isValidated')),
        );
    }

    public function isValidated(User $user){
        return $user->getEnable() && !is_null($user->getDateValidation());
    }

}

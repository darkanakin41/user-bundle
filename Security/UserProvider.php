<?php

namespace PLejeune\UserBundle\Security;


use PLejeune\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{

    /**
     * @var ManagerRegistry
     */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        $repository = $this->getRepository();
        $user = $repository->findOneBy(array("username" => $username));
        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        return $user;
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByEmail($username)
    {
        $repository = $this->getRepository();
        $user = $repository->findOneBy(array("email" => $username));
        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = $this->getClass();
        if (!$user instanceof $class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $repository = $this->getRepository();
        if ($repository instanceof UserProviderInterface) {
            $refreshedUser = $repository->refreshUser($user);
        } else {
            $refreshedUser = $repository->find($user->getId());
            if (null === $refreshedUser) {
                throw new UsernameNotFoundException(sprintf('User with id %s not found', json_encode($user->getId())));
            }
        }

        return $refreshedUser;
    }

    private function getRepository()
    {
        return $this->registry->getRepository(User::class);
    }

    private function getClass()
    {
        return User::class;
    }

    public function supportsClass($class)
    {
        return $class === $this->getClass();
    }


}
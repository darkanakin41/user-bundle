<?php

namespace PLejeune\UserBundle\Repository;

use PLejeune\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findFromCredentials($username){
        $qb = $this->createQueryBuilder("u");
        $qb->where("(u.username = :username OR u.email = :username)");
        $qb->setParameter("username", $username);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findFromRole($role){
        $qb = $this->createQueryBuilder("u");
        $qb->where($qb->expr()->like("u.roles", ":role"));
        $qb->setParameter("role", "%" . $role . "%");

        return $qb->getQuery()->getResult();
    }

}

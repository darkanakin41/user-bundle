<?php

namespace Darkanakin41\UserBundle\Repository;

use Darkanakin41\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findFromCredentials($username)
    {
        $qb = $this->createQueryBuilder("u");
        $qb->where("(u.username = :username OR u.email = :username)");
        $qb->setParameter("username", $username);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findFromRole($role)
    {
        $qb = $this->createQueryBuilder("u");
        $qb->where($qb->expr()->like("u.roles", ":role"));
        $qb->setParameter("role", "%".$role."%");

        return $qb->getQuery()->getResult();
    }

}

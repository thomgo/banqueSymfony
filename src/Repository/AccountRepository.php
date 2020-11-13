<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }


    public function getAccount(int $id, User $user): ?Account
    {
        return $this->createQueryBuilder('a')
            ->innerJoin("a.operations", "o")
            ->addSelect("o")
            ->where('a.id = :id')
            ->andWhere('a.user = :user')
            ->setParameters([
              "id" => $id,
              "user" => $user
            ])
            ->orderBy("o.registeringDate", "DESC")
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}

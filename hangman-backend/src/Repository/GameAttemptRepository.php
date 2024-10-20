<?php
// src/Repository/GameAttemptRepository.php

namespace App\Repository;

use App\Entity\GameAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameAttempt[]    findAll()
 * @method GameAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameAttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameAttempt::class);
    }
}

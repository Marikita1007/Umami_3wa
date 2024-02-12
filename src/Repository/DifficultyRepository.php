<?php

namespace App\Repository;

use App\Entity\Difficulty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Difficulty>
 *
 * @method Difficulty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Difficulty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Difficulty[]    findAll()
 * @method Difficulty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DifficultyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Difficulty::class);
    }

    public function add(Difficulty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Difficulty $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    //    /**
    //     * @return Wish[] Returns an array of Wish objects
    //     */
    //

        public function findByCategory(string $category)
        {
            return $this->createQueryBuilder('w')
                ->leftJoin('w.category','c' )
                ->addSelect('c')
                ->where('c.name = :c_name')
                ->setParameter('c_name',$category)
                ->getQuery()
                ->getResult();
        }
    public function findByDateASC()
    {
        return $this->createQueryBuilder('w')
            ->addSelect('w')
            ->orderBy('w.dateCreated', 'ASC')
            ->getQuery()
            ;
    }
    public function findByDateDESC()
    {
        return $this->createQueryBuilder('w')
            ->addSelect('w')
            ->orderBy('w.dateCreated', 'DESC')
            ->getQuery()
            ;
    }
}

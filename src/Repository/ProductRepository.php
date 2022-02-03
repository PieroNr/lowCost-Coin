<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllProductOrderByDate()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.publishedAt IS NOT NULL')
            //->setParameter('val', $value)
            ->orderBy('p.publishedAt', 'DESC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $value
     * @return int|mixed|string
     */

    public function searchByName($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.name LIKE :val OR q.slug LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupère les produits en lien avec une recherche
     * @return Product[]
     */
    public function findSearch(SearchData $search): array
    {

        $query = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.publishedAt', 'DESC');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->min)) {
            $query = $query
                ->andWhere('p.price >= :min')
                ->setParameter('min', $search->min);
        }

        if (!empty($search->max)) {
            $query = $query
                ->andWhere('p.price <= :max')
                ->setParameter('max', $search->max);
        }


        if (!empty($search->tags)) {
            $query = $query
                ->addSelect('t')
                ->join('p.tags', 't')
                ->andWhere('t.id IN (:tags)')
                ->setParameter('tags', $search->tags);
        }

        return $query->getQuery()->getResult();
    }

    private function getSearchQuery(SearchData $search, $ignorePrice = false): QueryBuilder
    {
    }



    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

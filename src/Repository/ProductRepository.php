<?php

namespace App\Repository;

use App\Entity\Product;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    public function findProductsFromLastSixMonth(): array
    {
        $lastMonth = (new DateTime())->modify('-6 month');

        return $this->createQueryBuilder('p')
            ->where('p.created_at >= :lastMonth')
            ->setParameter('lastMonth', $lastMonth)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findProductsFromLastSixMonthWithBrandId($pk): array
    {
        $lastMonth = (new DateTime())->modify('-6 month');
        return $this->createQueryBuilder('p')
            ->where('p.created_at >= :lastMonth')
            ->andWhere('p.fkBrand = :pk')
            ->setParameter('lastMonth', $lastMonth)
            ->setParameter('pk', $pk)
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findPriceRangeFromLastSixMonth(): array
    {
        $lastMonth = (new DateTime())->modify('-6 month');

        $query = $this->createQueryBuilder('p')
            ->select('MIN(p.regular_price) as minPrice, MAX(p.regularPrice) as maxPrice')
            ->where('p.created_at >= :lastMonth')
            ->setParameter('lastMonth', $lastMonth)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int|null $categoryId
     * @param int $minPrice
     * @param int $maxPrice
     * @return Product[]
     */
    public function findByPriceRangeAndCategory(?int $categoryId, int $minPrice, int $maxPrice): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.solde_price BETWEEN :minPrice AND :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice);

        if ($categoryId) {
            $qb->leftJoin('p.categories', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int|null $categoryId
     * @param int $minPrice
     * @param int $maxPrice
     * @return Product[]
     */
        public function findByPriceRangeAndCategoryBrand(?int $categoryId, ?int $minPrice, ?int $maxPrice, array $brands): array
        {
           
          /*  dd($brands);
            $qb = $this->createQueryBuilder('p')
                ->where('p.solde_price BETWEEN :minPrice AND :maxPrice')
                ->andWhere('p.fkBrand IN (:brands)')
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice)
                ->setParameter('brands',$brands); // Passer $brands en tant que tableau
        
            if ($categoryId) {
                $qb->leftJoin('p.categories', 'c')
                    ->andWhere('c.id = :categoryId')
                    ->setParameter('categoryId', $categoryId);
            }*/
            // dd($brands);
            $qb = $this->createQueryBuilder('p');

            // Filtrage par catégorie si fourni
            if ($categoryId) {
               // dd("ok");
                $qb->leftJoin('p.categories', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
            }

            // Filtrage par plage de prix
            if ($minPrice !== null && $maxPrice !== null) {
                //dd($minPrice,$maxPrice);
                $qb->andWhere('p.solde_price BETWEEN :minPrice AND :maxPrice')
                ->setParameter('minPrice', $minPrice)
                ->setParameter('maxPrice', $maxPrice);
            }
            
            // Filtrage par marques si fournies
            if (!empty($brands)) {
               // dd(count($brands),$brands);
                $qb->andWhere('p.fkBrand IN (:brands)')
                ->setParameter('brands', $brands);
            }
          ///  dd($categoryId,$minPrice,$maxPrice,$brands, $qb->getQuery()->getResult());
            return $qb->getQuery()->getResult();
        
        
           // return $qb->getQuery()->getResult();
        }
    
//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

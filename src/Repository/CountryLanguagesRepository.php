<?php

namespace App\Repository;

use App\Entity\CountryLanguages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CountryLanguages>
 *
 * @method CountryLanguages|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountryLanguages|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountryLanguages[]    findAll()
 * @method CountryLanguages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryLanguagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CountryLanguages::class);
    }

    public function save(CountryLanguages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CountryLanguages $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getLanguageByCountry($country_id){
        $language_id = [];
        $em = $this->getEntityManager();
        $query = $em->createQuery('
            SELECT IDENTITY(cl.language_id) FROM App\Entity\CountryLanguages cl WHERE cl.country_id = :id  
        ')
        ->setParameter(':id', $country_id);
        $result_query = $query->getArrayResult();
        //dd(gettype($result_query));
        foreach ($result_query as $key => $value){
            array_push($language_id, $value[1]);
        }
        // SECOND QUERY TO GET NAMES
        $query2 = $em->createQuery('
            SELECT l.name FROM App\Entity\Language l WHERE l.id IN(:language_id)
        ')
        ->setParameter('language_id', $language_id);
        $result_query2 = $query2->getArrayResult();
        return $result_query2;
    }

//    /**
//     * @return CountryLanguages[] Returns an array of CountryLanguages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CountryLanguages
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

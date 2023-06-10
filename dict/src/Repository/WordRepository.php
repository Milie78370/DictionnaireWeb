<?php

namespace App\Repository;

use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @extends ServiceEntityRepository<Word>
 *
 * @method Word|null find($id, $lockMode = null, $lockVersion = null)
 * @method Word|null findOneBy(array $criteria, array $orderBy = null)
 * @method Word[]    findAll()
 * @method Word[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }

    public function save(Word $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Word $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


/**
* @return Word[] Requête permettant de récupérer tous les 
* les mots du dictionnaire entrés par un utilisateur donné
*/
public function findByWordByUser($value): array
{
    return $this->createQueryBuilder('w')
        ->leftJoin('w.user ','u')
        ->orderBy('u.id', 'ASC')
        ->where('u.id=:cp')
        ->setParameter('cp', $value)
        ->getQuery()
        ->getResult()
    ;
}


/**
* @return Word[] Requête permettant de récupérer tous les 
* les mots du dictionnaire entrés par un utilisateur donné
*/
public function findByLanguage($value): array
{
    return $this->createQueryBuilder('w')
        ->leftJoin('w.language ','u')
        ->andWhere('u.name = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
    ;
}


/**
* @return Word[] Requête permettant de récupérer tous les 
* les mots du dictionnaire entrés par une catégorie donnée 
*/
public function findByGroupByUser($value): array
{
    return $this->createQueryBuilder('w')
        ->leftJoin('w.groupWord ','u')
        ->orderBy('u.id', 'ASC')
        ->where('u.id=:cp')
        ->setParameter('cp', $value)
        ->getQuery()
        ->getResult()
    ;
}


/**
* @return Word[] Requête permettant de récupérer tous les 
* les mots du dictionnaire entrés par une catégorie donnée 
*/
public function findByGroupByUserCategorie($value, $categorie): array
{
    return $this->createQueryBuilder('w')
    ->leftJoin('w.user ','u')
    ->leftJoin('u.groupWord ','c')
    ->orderBy('c.id', 'ASC')
    ->andWhere('u.id=:val')
    ->andWhere('c.id=:cp')
    ->setParameter('val', $value)
    ->setParameter('cp', $categorie)
    ->getQuery()
    ->getResult()
;
}

/**
* @return Word[] Requête permettant de récupérer tous les 
* les mots du dictionnaire entrés par un utilisateur donné
*/
public function findWord($value): array
{
    return $this->createQueryBuilder('w')
        ->orderBy('w.id', 'ASC')
        ->orWhere('w.def =:val')
        ->setParameter('val', $value)
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
}


//    public function findOneBySomeField($value): ?Word
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

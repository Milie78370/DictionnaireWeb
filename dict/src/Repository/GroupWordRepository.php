<?php

namespace App\Repository;

use App\Entity\GroupWord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupWord>
 *
 * @method GroupWord|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupWord|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupWord[]    findAll()
 * @method GroupWord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupWordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupWord::class);
    }

    public function save(GroupWord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(GroupWord $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

/**
* @return GroupWord[] Requête permettant de récupérer tous les 
* les mots du dictionnaire entrés par un utilisateur donné
*/
public function filterWordByCategorie(): array
{
    return $this->createQueryBuilder('w')
        ->orderBy('w.label', 'ASC')
        ->groupBy('w.label')
        ->getQuery()
        ->getResult()
    ;
}


//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupWord
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

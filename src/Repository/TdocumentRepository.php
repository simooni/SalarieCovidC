<?php

namespace App\Repository;

use App\Entity\Tdocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tdocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tdocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tdocument[]    findAll()
 * @method Tdocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TdocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tdocument::class);
    }

    // /**
    //  * @return Tdocument[] Returns an array of Tdocument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    
    public function findByDocumentUser()
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.user_create', 'u')
            // ->select( 'u.id, u.user, u.nom, u.prenom, u.cin, t.file' )
            ->Where('t.supprimer =0')
            ->getQuery()
            ->getResult()   
        ;
    }
 
    // public function findOneByDateRenderVous($id_etoudiant)
    // {
    //     return $this->createQueryBuilder('r')
    //         ->select( 'max(r.dateRV)' )
    //         ->Where('r.etudiant = :id_etoud')
    //         ->setParameter('id_etoud', $id_etoudiant)
    //         ->groupBy('r.dateRV')
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
    
}

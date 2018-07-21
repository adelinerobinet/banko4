<?php

namespace App\Repository;

use App\Entity\MouvementAutomatique;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MouvementAutomatique|null find($id, $lockMode = null, $lockVersion = null)
 * @method MouvementAutomatique|null findOneBy(array $criteria, array $orderBy = null)
 * @method MouvementAutomatique[]    findAll()
 * @method MouvementAutomatique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementAutomatiqueRepository extends ServiceEntityRepository
{
    /**
     * MouvementAutomatiqueRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MouvementAutomatique::class);
    }

   /**
    * Retourne les mouvements automatiques d'un compte
    *
    * @return array
    */
    public function getMouvementAutomatiqueActifCompte($compte_id) 
    {
    	$qb = $this->_em->createQueryBuilder()
                ->select("ma")
    		->from("App:MouvementAutomatique", "ma")
    		->where("ma.compte = '" . $compte_id . "'")
                ->andWhere("ma.actif = 1");

        return $qb->getQuery()->getArrayResult();
    }  
}

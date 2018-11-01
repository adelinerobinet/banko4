<?php

namespace App\Repository;

use App\Entity\MouvementAutomatique;
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
    * @param $id : Id du compte
    * @return array
    */
    public function getMouvementAutomatiqueActifCompte($id)
    {
    	$qb = $this->_em->createQueryBuilder()
            ->select('ma')
    		->from('App:MouvementAutomatique', 'ma')
    		->where('ma.compte = :compte')
            ->setParameter('compte', $id)
            ->andWhere('ma.actif = 1');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Retourne le montant des frais commun
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findMonthlyCommonFees()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('SUM(ma.debit) - SUM(ma.credit)')
            ->from('App:MouvementAutomatique', 'ma')
            ->where('ma.commun = true')
	    ->andWhere('ma.actif = true');

        return floatval($qb->getQuery()->getSingleScalarResult());
    }
}

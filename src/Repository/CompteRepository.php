<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Compte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compte[]    findAll()
 * @method Compte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteRepository extends ServiceEntityRepository
{
    /**
     * CompteRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Compte::class);
    }

    /**
     * Retourne le montant du compte courant passé en paramètre
     *
     * @param $compteId
     * @return mixed
     */
    public function getMontantCompteCourant($compteId)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('SUM(m.credit) AS totalCreditTraite, SUM(m.debit) AS totalDebitTraite')
            ->from("App:Mouvement", "m")
            ->where("m.compte = '" . $compteId . "'")
            ->andWhere('m.traite = 1');

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * Retourne le montant du compte prévisionnel
     *
     * @param $compteId
     * @return mixed
     */
    public function getMontantComptePrevisionnel($compteId)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('SUM(m.credit) AS totalCredit, SUM(m.debit) AS totalDebit')
            ->from("App:Mouvement", "m")
            ->where("m.compte = '" . $compteId . "'");

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}

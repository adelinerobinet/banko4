<?php

namespace App\Repository;

use App\Entity\Compte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * Récupération des comptes en tant que tableau
     * @return array
     */
    public function findByAsArray()
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('c.id, c.nom, c.soldeInitial as solde_initial')
            ->from('App:Compte', 'c')
            ->orderBy('c.ordre', 'ASC');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Retourne le montant du compte courant passé en paramètre
     *
     * @param Compte $compte
     * @return mixed
     */
    public function getMontantCompteCourant($compte)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('SUM(m.credit) AS totalCreditTraite, SUM(m.debit) AS totalDebitTraite')
            ->from('App:Mouvement', 'm')
            ->where('m.compte = :compte')
            ->setParameter('compte', $compte)
            ->andWhere('m.traite = 1');

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Retourne le montant du compte prévisionnel
     *
     * @param Compte $compte
     * @return mixed
     */
    public function getMontantComptePrevisionnel($compte)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('SUM(m.credit) AS totalCredit, SUM(m.debit) AS totalDebit')
            ->from('App:Mouvement', 'm')
            ->where('m.compte = :compte')
            ->setParameter('compte', $compte);

        return $qb->getQuery()->getArrayResult();
    }
}

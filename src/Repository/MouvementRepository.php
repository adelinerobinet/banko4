<?php

namespace App\Repository;

use App\Entity\Compte;
use App\Entity\Mouvement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Mouvement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mouvement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mouvement[]    findAll()
 * @method Mouvement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementRepository extends ServiceEntityRepository
{
    /**
     * MouvementRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Mouvement::class);
    }

    /**
     * Retourne le mouvement s'il existe déjà pour ce compte
     *
     * @param $id : Id du compte
     * @param $libelle
     * @param $date
     * @param $credit
     * @param $debit
     * @return mixed
     */
    public function getMouvementAutomatiqueCompte($id, $libelle, $date, $credit, $debit)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('m')
            ->from('App:Mouvement', 'm')
            ->where('m.compte = :compte')
            ->andWhere('m.libelle = :libelle')
            ->andWhere('m.date = :date')
            ->andWhere('m.credit = :credit')
            ->andWhere('m.debit = :debit')
            ->setParameter('compte', $id)
            ->setParameter('libelle', $libelle)
            ->setParameter('date', $date)
            ->setParameter('credit', $credit)
            ->setParameter('debit', $debit);

        return $query->getQuery()->getArrayResult();
    }

    /**
     * Récupération des derniers mouvements du compte
     *
     * @param Compte $compte
     * @return array
     */
    public function getMouvements(Compte $compte)
    {
        $qb = $this->createQueryBuilder('m')
            ->where('m.compte = :compte')
            ->setParameter('compte', $compte)
            ->orderBy('m.date', 'DESC')
            ->setMaxResults(Mouvement::PER_PAGE);

        return $qb->getQuery()->getResult();
    }

    /**
     * Compte le nombre de mouvements selon un compte
     *
     * @param Compte $compte
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return integer
     */
    public function countMouvementByCompte(Compte $compte)
    {
        // Sinon, si on veut le nombre
        $qb = $this->createQueryBuilder('m')
            ->select('COUNT(m)')
            ->andWhere('m.compte = :compte')
            ->setParameter('compte', $compte);

        return intval($qb->getQuery()->getSingleScalarResult());
    }
}

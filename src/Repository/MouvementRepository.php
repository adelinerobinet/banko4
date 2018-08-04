<?php

namespace App\Repository;

use App\Entity\Compte;
use App\Entity\Mouvement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
     * @return array
     * @access public
     */
    public function getMouvementAutomatiqueCompte($compte_id, $libelle, $date, $credit, $debit)
    {
        $query = $this->_em->createQueryBuilder()
            ->select("m")
            ->from("App:Mouvement", "m")
            ->where("m.compte = '" . $compte_id . "'")
            ->andWhere("m.libelle = '" . $libelle . "'")
            ->andWhere("m.date = '" . $date . "'")
            ->andWhere("m.credit = '" . $credit . "'")
            ->andWhere("m.debit = '" . $debit . "'");

        return $query->getQuery()->getResult(Query::HYDRATE_ARRAY);
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

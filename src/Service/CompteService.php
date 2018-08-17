<?php

namespace App\Service;

use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Compte;

/**
 * Class CompteService
 * @package App\Service
 */
class CompteService
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var CompteRepository $compteRepository */
    private $compteRepository;

    /**
     * CompteService constructor
     *
     * @param EntityManagerInterface $entityManager
     * @param CompteRepository $compteRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CompteRepository $compteRepository)
    {
        $this->em = $entityManager;
        $this->compteRepository = $compteRepository;
    }

    /**
     * Retourne le solde courant et le solde prévisionnel du compte
     *
     * @param integer $id : Id du compte
     * @return array
     */
    public function getSolde($id)
    {
        // Initialisation
        $data = [];

        /** @var Compte $compte */
        $compte = $this->compteRepository->find($id);

        // Récupération du solde courant
        $compteCourant = $this->compteRepository->getMontantCompteCourant($compte);
        $data['courant'] = round($compte->getSoldeInitial() + $compteCourant[0]['totalCreditTraite'] - $compteCourant[0]['totalDebitTraite'],2);

        // Récupération du solde prévisionnel
        $comptePrevisionnel = $this->compteRepository->getMontantComptePrevisionnel($compte);
        $data['previsionnel'] = round($compte->getSoldeInitial() + $comptePrevisionnel[0]['totalCredit'] - $comptePrevisionnel[0]['totalDebit'],2);

        return $data;
    }

    /**
     * Retourne tous les comptes avec le solde courant et le solde prévisionnel
     *
     * @return array
     */
    public function getAllAsArray()
    {
        // Récupération de la liste des comptes
        $comptes = $this->compteRepository->findByAsArray();

        // Récupération du solde courant et prévisionnel de chaque compte
        foreach ($comptes as $key => $compte) {
            $solde = $this->getSolde($compte['id']);

            $comptes[$key]['solde_courant'] = $solde['courant'];
            $comptes[$key]['solde_previsionnel'] = $solde['previsionnel'];
        }

        return $comptes;
    }
}
<?php

namespace App\Controller;

use App\Repository\CompteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends Controller
{
    /**
     * Liste les comptes
     *
     * @Route("/", name="home", methods="GET")
     *
     * @param CompteRepository $compteRepository
     * @return Response
     */
    public function indexAction(CompteRepository $compteRepository)
    {
        // Récupération de la liste des comptes
        $comptes = $compteRepository->findAll();

        //Récupération du solde courant et prévisionnel de chaque compte
        foreach ($comptes as $compte)
        {
            $compteCourant = $compteRepository->getMontantCompteCourant($compte->getId());
            $soldeCourant[$compte->getId()] = round($compte->getSoldeInitial() + $compteCourant[0]['totalCreditTraite'] - $compteCourant[0]['totalDebitTraite'], 2);

            $comptePrevisionnel = $compteRepository->getMontantComptePrevisionnel($compte->getId());
            $soldePrevisionnel[$compte->getId()] = round($compte->getSoldeInitial() + $comptePrevisionnel[0]['totalCredit'] - $comptePrevisionnel[0]['totalDebit'], 2);
        }

        // Mais pour l'instant, on ne fait qu'appeler le template
        return $this->render('Home/index.html.twig', [
            'comptes' => $comptes,
            'solde_courant' => $soldeCourant,
            'solde_previsionnel' => $soldePrevisionnel
        ]);
    }
}
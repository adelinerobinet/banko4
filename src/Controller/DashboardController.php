<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Repository\CompteRepository;
use SaadTazi\GChartBundle\DataTable\DataColumn;
use SaadTazi\GChartBundle\DataTable\DataTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DashboardController extends Controller
{
    /**
     * Liste les comptes
     *
     * @Route("", name="home", methods="GET")
     *
     * @param CompteRepository $compteRepository
     * @return Response
     */
    public function indexAction(CompteRepository $compteRepository)
    {
        // Récupération de la liste des comptes
        $comptes = $compteRepository->findBy([], ['ordre' => 'ASC']);

        //Récupération du solde courant et prévisionnel de chaque compte
        foreach ($comptes as $compte)
        {
            $compteCourant = $compteRepository->getMontantCompteCourant($compte->getId());
            $soldeCourant[$compte->getId()] = round($compte->getSoldeInitial() + $compteCourant[0]['totalCreditTraite'] - $compteCourant[0]['totalDebitTraite'], 2);

            $comptePrevisionnel = $compteRepository->getMontantComptePrevisionnel($compte->getId());
            $soldePrevisionnel[$compte->getId()] = round($compte->getSoldeInitial() + $comptePrevisionnel[0]['totalCredit'] - $comptePrevisionnel[0]['totalDebit'], 2);
        }

        // Mais pour l'instant, on ne fait qu'appeler le template
        return $this->render('Dashboard/index.html.twig', [
            'comptes' => $comptes,
            'solde_courant' => $soldeCourant,
            'solde_previsionnel' => $soldePrevisionnel
        ]);
    }

    /**
     * @Route("/compte/stats", name="stats")
     *
     * @param CompteRepository $compteRepository
     * @return Response
     * @throws \SaadTazi\GChartBundle\DataTable\Exception\InvalidColumnTypeException
     */
    public function stats(CompteRepository $compteRepository)
    {
        // Ici, on récupérera la liste des comptes, puis on la passera au template
        $comptes = $compteRepository->findBy([], ['ordre' => 'ASC']);

        /*
         * dataTable for Bar Chart for example (3 columns)
         */
        $dataTable2 = new DataTable();
        $dataTable2->addColumn('id1', 'label 1', 'string');
        $dataTable2->addColumnObject(new DataColumn('id2', 'Courant', 'number'));
        $dataTable2->addColumnObject(new DataColumn('id3', 'Prévisionnel', 'number'));

        //Ici, on récupère les soldes courant et prévisionnel de chaque compte
        /** @var Compte $compte */
        foreach ($comptes as $compte) {
            //Récupération du nom du compte
            $nomCompte = $compte->getNom();

            //Récupération du montant courant du compte
            $compteCourant = $compteRepository->getMontantCompteCourant($compte->getId());
            $soldeCourant = $compte->getSoldeInitial() + $compteCourant[0]['totalCreditTraite'] - $compteCourant[0]['totalDebitTraite'];

            //Récupération du montant prévisionnel du compte
            $comptePrevisionnel = $compteRepository->getMontantComptePrevisionnel($compte->getId());
            $soldePrevisionnel = $compte->getSoldeInitial() + $comptePrevisionnel[0]['totalCredit'] - $comptePrevisionnel[0]['totalDebit'];

            $dataTable2->addRow([$nomCompte, $soldeCourant, $soldePrevisionnel]);
        }

        return $this->render('Dashboard/stats.html.twig', [
            'dataTable2' => $dataTable2->toArray()
        ]);
    }
}
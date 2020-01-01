<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Service\CompteService;
use SaadTazi\GChartBundle\DataTable\DataColumn;
use SaadTazi\GChartBundle\DataTable\DataTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * Liste les comptes
     *
     * @Route("", name="dashboard", methods="GET")
     *
     * @param CompteService $compteService
     * @return Response
     */
    public function index(CompteService $compteService)
    {
        // Mais pour l'instant, on ne fait qu'appeler le template
        return $this->render('Dashboard/index.html.twig', [
            'comptes' => $compteService->getAllAsArray(),
            'total' => $compteService->getTotal()
        ]);
    }

    /**
     * @Route("/stats", name="stats")
     *
     * @param CompteService $compteService
     * @return Response
     * @throws \SaadTazi\GChartBundle\DataTable\Exception\InvalidColumnTypeException
     */
    public function stats(CompteService $compteService)
    {
        // Récupération de tous les comptes
        $comptes = $compteService->getAllAsArray();

        // Paramétrage des colonnes
        $dataTable2 = new DataTable();
        $dataTable2->addColumn('id1', 'label 1', 'string');
        $dataTable2->addColumnObject(new DataColumn('id2', 'Courant', 'number'));
        $dataTable2->addColumnObject(new DataColumn('id3', 'Prévisionnel', 'number'));

        /** @var Compte $compte */
        foreach ($comptes as $compte) {
            // Récupération du solde courant et prévisionnel
            $dataTable2->addRow([$compte['nom'], $compte['solde_courant'], $compte['solde_previsionnel']]);
        }

        return $this->render('Dashboard/stats.html.twig', [
            'dataTable2' => $dataTable2->toArray()
        ]);
    }
}
<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Mouvement;
use App\Service\CompteService;
use App\Service\MouvementService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompteController
 * @package App\Controller
 *
 * @Route("/compte")
 */
class CompteController extends Controller
{
    /**
     * Modification des mouvements d'un compte
     *
     * @Route("/{id}/edit", name="compte_edit", methods="GET|POST")
     *
     * @param Request $request
     * @param Compte $compte
     * @param CompteService $compteService
     * @param MouvementService $mouvementService
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Request $request,
        Compte $compte,
        CompteService $compteService,
        MouvementService $mouvementService,
        PaginatorInterface $paginator
    ) {
        // Appel du traitement de l'ajout des prelevements automatiques du mois en cours pour le compte à afficher
        $mouvementService->ajoutPrelevementAutomatique($compte);

        // On récupère le solde courant et prévisionnel
        $solde = $compteService->getSolde($compte->getId());

        // Pagination
        $mouvements = $compte->getMouvements();
        $pagination = $paginator->paginate($mouvements, $request->query->getInt('page', 1), Mouvement::PER_PAGE);

        return $this->render('Compte/edit.html.twig', [
            'compte' => $compte,
            'count_mouvement' => count($mouvements),
            'solde' => $solde,
            'pagination' => $pagination,
        ]);
    }
}
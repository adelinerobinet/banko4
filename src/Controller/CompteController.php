<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\CompteType;
use App\Repository\MouvementRepository;
use App\Service\CompteService;
use App\Service\MouvementService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CompteController
 * @package App\Controller
 *
 *  @Route("/compte")
 */
class CompteController extends Controller
{
    /**
     * @Route("/{id}/edit", name="compte_edit", methods="GET|PUT")
     *
     * @param Request $request
     * @param Compte $compte
     * @param MouvementRepository $mvtRepository,
     * @param CompteService $compteService
     * @param MouvementService $mouvementService
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Request $request,
        Compte $compte,
        MouvementRepository $mvtRepository,
        CompteService $compteService,
        MouvementService $mouvementService,
        EntityManagerInterface $em
    ) {
        // Appel du traitement de l'ajout des prelevements automatiques du mois en cours pour le compte à afficher
        $mouvementService->ajoutPrelevementAutomatique($compte);

        // On récupère le solde courant et prévisionnel
        $solde = $compteService->getSolde($compte->getId());

        // Récupération des derniers mouvements du compte
        $mouvements = $mvtRepository->getMouvements($compte);

        $form = $this->createForm(CompteType::class, $compte, [
            'method' => 'PUT',
            'mouvements' => $mouvements
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'compte_success_edit');

            return $this->redirect($request->getUri());
        }

        return $this->render('Compte/edit.html.twig', [
            'solde' => $solde,
            'form' => $form->createView(),
      ]);
    }
}
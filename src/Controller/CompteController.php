<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Mouvement;
use App\Form\CompteType;
use App\Repository\CompteRepository;
use App\Service\CompteService;
use App\Service\MouvementService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
     * @param CompteRepository $compteRepository
     * @param CompteService $compteService
     * @param MouvementService $mouvementService
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Request $request,
        Compte $compte,
        CompteRepository $compteRepository,
        CompteService $compteService,
        MouvementService $mouvementService,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // TODO : A faire avec Twig - Récupération de tous les comptes pour l'affichage dans le menu
        $comptes = $compteRepository->findBy([], ['ordre' => 'ASC']);

        // Appel du traitement de l'ajout des prelevements automatiques du mois en cours pour le compte à afficher
        $mouvementService->ajoutPrelevementAutomatique($compte);

        // On récupère le solde courant et prévisionnel
        $solde = $compteService->getSolde($compte->getId());

        // Pagination
        $mouvements = $compte->getMouvements();
        $pagination = $paginator->paginate($mouvements, $request->query->getInt('page', 1), Mouvement::PER_PAGE);

        $form = $this->createForm(CompteType::class, $compte, [
            'method' => 'PUT',
            'pagination' => $pagination
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'compte_success_edit');

            return $this->redirect($request->getUri());
        }

        return $this->render('Compte/edit.html.twig', [
            'comptes' => $comptes,
            'solde' => $solde,
            'pagination' => $pagination,
            'form' => $form->createView(),
      ]);
    }
}
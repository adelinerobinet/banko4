<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Mouvement;
use App\Repository\CompteRepository;
use App\Repository\MouvementRepository;
use App\Service\MouvementService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends Controller
{
    /**
     * @Route("/show/{compte}", name="show", methods="GET")
     *
     * @param Request $request
     * @param Compte $compte
     * @param CompteRepository $compteRepository
     * @param MouvementRepository $mouvementRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function show(Request $request, Compte $compte, CompteRepository $compteRepository, MouvementRepository $mouvementRepository, MouvementService $mouvementService)
    {
        // Récupération de tous les comptes pour l'affichage dans le menu
        $comptes = $compteRepository->findBy([], ['ordre' => 'ASC']);

        //Appel du traitement de l'ajout des prelevements automatiques du mois en cours pour le compte à afficher
        $mouvementService->ajoutPrelevementAutomatique($compte);

        // On récupère la liste des mouvements par rapport au compte
        $mouvements = $mouvementRepository->getMouvementsCompte($compte->getId(), Mouvement::PER_PAGE, $request->query->get('page', 1));

        //On récupère le solde courant (initial + totalCreditTraite - totalDebitTraite)
        $compteCourant = $compteRepository->getMontantCompteCourant($compte->getId());
        $soldeCourant = round($compte->getSoldeInitial() + $compteCourant[0]['totalCreditTraite'] - $compteCourant[0]['totalDebitTraite'], 2);

        //On récupère le solde courant (initial + totalCredit - totalDebit)
        $comptePrevisionnel = $compteRepository->getMontantComptePrevisionnel($compte->getId());
        $soldePrevisionnel = round($compte->getSoldeInitial() + $comptePrevisionnel[0]['totalCredit'] - $comptePrevisionnel[0]['totalDebit'], 2) ;

        // Récupération du nombre de mouvements
        $countMouvements = $mouvementRepository->countMouvementByCompte($compte);

        // Récupération des informations de la pagination
        $pagination = [
            'page' => $request->query->get('page', 1),
            'pages_count' => max(ceil($countMouvements / Mouvement::PER_PAGE), 1),
        ];

        // Puis modifiez la ligne du render comme ceci, pour prendre en compte l'article :
        return $this->render('Compte/show.html.twig', [
            'compte' => $compte,
            'comptes' => $comptes,
            'solde_courant' => $soldeCourant,
            'solde_previsionnel' => $soldePrevisionnel,
            'mouvements' => $mouvements,
            'pagination' => $pagination,
      ]);
    }

    /**
     * Suppression du mouvement
     *
     * @Route("/{id}/delete", name="mouvement_delete", methods="DELETE|GET")
     *
     * @param Mouvement $mouvement
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Mouvement $mouvement)
    {
        /** @var Compte $compte */
        $compte = $mouvement->getCompte();

        if (is_object($mouvement)) {
            $this->getDoctrine()->getManager()->remove($mouvement);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'mouvement_success_delete');

            return $this->redirect($this->get('router')->generate('show', ['compte' => $compte->getId()]));
        }

        $this->addFlash('danger', 'mouvement_error_delete');
    }
}
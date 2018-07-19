<?php

namespace App\Controller;

use App\Repository\CompteRepository;
use App\Repository\MouvementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compte")
 */
class CompteController extends Controller
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
    
    /**
     * @Route("/voir/{id}/{page}", name="show", methods="GET")
     */
    public function show($id, $page, CompteRepository $compteRepository, MouvementRepository $mouvementRepository)
    {
      $compte = $compteRepository->find($id);
      $comptes = $compteRepository->findBy([], ['ordre' => 'ASC']);

      if($compte == null)
      {
          throw $this->createNotFoundException('Compte [id='.$id.'] inexistant.');
      }

      //Appel du traitement de l'ajout des prelevements automatiques du mois en cours pour le compte à afficher
      //$mouvementService->ajoutPrelevementAutomatique($compte);

      // On récupère la liste des mouvements par rapport au compte
      //$liste_mouvements = $em->getRepository('App:Mouvement')->findByCompte($id);
      $mouvements = $mouvementRepository->getMouvementsCompte($id, 15, $page);

      //On récupère le solde courant (initial + totalCreditTraite - totalDebitTraite)
      $compte_courant = $compteRepository->getMontantCompteCourant($id);
      $solde_courant = round($compte->getSoldeInitial() + $compte_courant[0]['totalCreditTraite'] - $compte_courant[0]['totalDebitTraite'], 2);

      //On récupère le solde courant (initial + totalCredit - totalDebit)
      $compte_previsionnel = $compteRepository->getMontantComptePrevisionnel($id);
      $solde_previsionnel = round($compte->getSoldeInitial() + $compte_previsionnel[0]['totalCredit'] - $compte_previsionnel[0]['totalDebit'], 2) ;

      // Puis modifiez la ligne du render comme ceci, pour prendre en compte l'article :
      return $this->render('Home/show.html.twig', [
        'compte' => $compte,
        'comptes' => $comptes,
        'solde_courant' => $solde_courant,
        'solde_previsionnel' => $solde_previsionnel,
        'mouvements' => $mouvements,
        'page' => $page,
        'nombre_page' => ceil(count($mouvements)/15),
      ]);
    }
}
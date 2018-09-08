<?php

namespace App\Controller;

use App\Service\MouvementService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MouvementAutomatiqueController
 * @package App\Controller
 *
 *  @Route("/mouvement-automatique")
 */
class MouvementAutomatiqueController extends Controller
{
    /**
     * Affichage des frais mensuels
     *
     * @Route("", name="mouvement_automatique_index", methods="GET|POST")
     *
     * @param MouvementService $mouvementService
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(MouvementService $mouvementService, EntityManagerInterface $em) {
        $all = $em->getRepository('App:MouvementAutomatique')->findBy(
            ['commun' => true],
            ['numeroJour' => 'ASC']
        );

        $amount = $mouvementService->getMonthlyCommonFees();

        return $this->render('MouvementAutomatique/index.html.twig', [
            'all' => $all,
            'amount' => $amount
        ]);
    }
}
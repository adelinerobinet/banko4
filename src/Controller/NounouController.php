<?php

namespace App\Controller;

use App\Service\MouvementService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NounouController
 * @package App\Controller
 *
 *  @Route("/nounou")
 */
class NounouController extends Controller
{
    /**
     * Affichage du calcul du salaire de la nounou
     *
     * @Route("", name="nounou_index", methods="GET|POST")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        return $this->render('Nounou/index.html.twig');
    }
}
<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\Mouvement;
use App\Form\CompteType;
use App\Form\MouvementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CompteController
 * @package App\Controller
 *
 *  @Route("/mouvement")
 */
class MouvementController extends Controller
{
    /**
     * Modification des mouvements d'un compte
     *
     * @Route("/new/{id}", name="mouvement_new", methods="GET|POST|PUT")
     *
     * @param Request $request
     * @param Compte $compte
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, Compte $compte, EntityManagerInterface $em) {
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'compte_success_edit');

            return $this->redirectToRoute('compte_edit', ['id' => $compte->getId()]);
        }

        return $this->render('Mouvement/new.html.twig', [
            'form' => $form->createView(),
            'id' => $compte
        ]);
    }

    /**
     * Modification d'un mouvement
     *
     * @param Request $request
     * @param Mouvement $mouvement
     * @Route("/{id}/edit", name="mouvement_update")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit(Request $request, Mouvement $mouvement)
    {
        $form = $this->createForm(MouvementType::class, $mouvement);
        $form->handleRequest($request);
        $compte = $mouvement->getCompte()->getId();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'mouvement_success_edit');

            return $this->redirectToRoute('compte_edit', ['id' => $compte]);
        }

        return $this->render('Mouvement/edit.html.twig', [
            'mouvement' => $mouvement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Suppression d'un mouvement
     *
     * @param Mouvement $mvt
     * @Route("/{id}/delete", name="mouvement_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Mouvement $mvt)
    {
        $em = $this->getDoctrine()->getManager();
        $compte = $mvt->getCompte()->getId();

        if (!$mvt) {
            throw $this->createNotFoundException('Unable to find Mouvement entity.');
        }

        $em->remove($mvt);
        $em->flush();

        return $this->redirectToRoute('compte_edit', ['id' => $compte]);
    }
}
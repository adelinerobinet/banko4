<?php

namespace App\Twig;

use Doctrine\ORM\EntityManagerInterface;

class AppExtension extends \Twig_Extension
{
    /** @var EntityManagerInterface $em */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Déclaration des functions ajoutées à Twig
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('comptes', [$this, 'comptes']),
        ];
    }

    /**
     * Permet de récupérer les comptes pour le menu
     *
     * @return \App\Entity\Compte[]
     */
    public function comptes()
    {
        return $this->em->getRepository('App:Compte')->findBy([], ['ordre' => 'ASC']);
    }
}

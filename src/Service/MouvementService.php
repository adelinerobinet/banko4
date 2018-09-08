<?php

namespace App\Service;

use App\Entity\Compte;
use App\Repository\MouvementAutomatiqueRepository;
use App\Repository\MouvementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Mouvement;

class MouvementService
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var MouvementRepository $mouvementRepository */
    protected $mouvementRepository;

    /** @var MouvementAutomatiqueRepository $mouvementAutomatiqueRepository */
    protected $mouvementAutomatiqueRepository;

    /**
     * MouvementService constructor.
     * @param EntityManagerInterface $entityManager
     * @param MouvementRepository $mouvementRepository
     * @param MouvementAutomatiqueRepository $mouvementAutomatiqueRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MouvementRepository $mouvementRepository,
        MouvementAutomatiqueRepository $mouvementAutomatiqueRepository
    ) {
        $this->em = $entityManager;
        $this->mouvementRepository = $mouvementRepository;
        $this->mouvementAutomatiqueRepository = $mouvementAutomatiqueRepository;
    }

    /**
     * Ajoute les mouvements automatiques si besoin
     *
     * @param $compte
     */
    public function ajoutPrelevementAutomatique(Compte $compte)
    {
        //Récupération des mouvements automatique actifs sur le compte
        $mouvement_automatique = $this->mouvementAutomatiqueRepository->getMouvementAutomatiqueActifCompte($compte->getId());

        // S'il existe des mouvements automatiques actifs sur le compte
        if (count($mouvement_automatique) > 0) {
            // Pour chaque mouvement automatique
            foreach ($mouvement_automatique as $mvt_auto) {
                $mouvement = new Mouvement();

                // Si on est à moins de 5 jours du mois suivant, on ajoute les prochains prelevements automatiques
                if (date('t', mktime(0, 0, 0, date('m'), 1, date('Y'))) - date('d') <= 5) {
                    // Si on est au mois de décembre on prend le mois de janvier sinon m+1
                    if (date('m') == 12) {
                        $mois_suivant = "01";
                    } else {
                        $mois_suivant = date('m') + 1;
                    }

                    // Si le mouvement automatique n'est pas ajouté au compte pour le mois suivant, on l'ajoute
                    if ($this->getMouvementCompte($compte->getId(), $mvt_auto['libelle'],
                            $mvt_auto['numeroJour'], $mvt_auto['credit'], $mvt_auto['debit'], $mois_suivant,
                            true) == false) {
                        $mouvement->setCompte($compte);
                        $mouvement->setTraite(0);
                        $mouvement->setLibelle($mvt_auto['libelle']);
                        //Si on est au mois de décembre on prend l'année suivante et le mois de janvier pour la date du prélevement auto
                        if (date('m') == 12) {
                            $mouvement->setDate(new \Datetime((date('Y') + 1) . '-' . ('01') . '-' . $mvt_auto['numeroJour']));
                        } else {
                            $mouvement->setDate(new \DateTime(date('Y') . '-' . (date('m') + 1) . '-' . $mvt_auto['numeroJour']));
                        }
                        $mouvement->setCredit($mvt_auto['credit']);
                        $mouvement->setDebit($mvt_auto['debit']);

                        $this->save($mouvement);
                    }
                } else {
                    // Si le mouvement automatique n'est pas ajouté au compte pour le mois en cours, on l'ajoute
                    if ($this->getMouvementCompte($compte->getId(), $mvt_auto['libelle'],
                            $mvt_auto['numeroJour'], $mvt_auto['credit'], $mvt_auto['debit'], date('m'),
                            false) == false) {
                        $mouvement->setCompte($compte);
                        $mouvement->setTraite(0);
                        $mouvement->setLibelle($mvt_auto['libelle']);
                        $mouvement->setDate(new \DateTime(date('Y') . '-' . date('m') . '-' . $mvt_auto['numeroJour']));
                        $mouvement->setCredit($mvt_auto['credit']);
                        $mouvement->setDebit($mvt_auto['debit']);

                        $this->save($mouvement);
                    }
                }
            }
        }
    }

    /**
     * Retourne si oui ou non le mouvement existe déjà sur le mois en cours du compte
     *
     * @param $compte_id
     * @param $libelle
     * @param $numero_jour
     * @param $credit
     * @param $debit
     * @param $mois
     * @param $annee_suivante
     * @return bool
     */
    private function getMouvementCompte($compte_id, $libelle, $numero_jour, $credit, $debit, $mois, $annee_suivante)
    {
        // Initialisation
        $date = null;

        if ($mois == date('m')) {
            $date = date('Y') . '-' . date('m') . '-' . $numero_jour;
        }

        //Si on veut afficher les prochains prelevements automatiques du mois suivant car on est à - de 9 jours du nouveau mois
        if ($mois == date('m') + 1) {
            $date = date('Y') . '-' . (date('m') + 1) . '-' . $numero_jour;
        }

        //Si on veut afficher les prochains prelevements automatiques du mois suivant spécifique janvier de l'année suivante car on est à - de 9 jours du nouveau mois
        if ($mois == "01" && $annee_suivante == true) {
            $date = (date('Y') + 1) . '-01-' . $numero_jour;
        }

        //Si on veut afficher les prochains prelevements automatiques du mois en coute spécifique janvier car on est à - de 9 jours du nouveau mois
        if ($mois == "01" && $annee_suivante == false) {
            $date = (date('Y') . '-01-' . $numero_jour);
        }

        $mouvement_automatique = $this->mouvementRepository->getMouvementAutomatiqueCompte(
            $compte_id,
            $libelle,
            $date,
            $credit,
            $debit
        );

        if (count($mouvement_automatique) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Retourne le montant des frais commun sur le mois
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMonthlyCommonFees()
    {
        $result = $this->em->getRepository('App:MouvementAutomatique')->findMonthlyCommonFees();

        return $result / 2;
    }

    /**
     * Enregistre un mouvement
     *
     * @param $mouvement
     */
    private function save($mouvement)
    {
        $this->em->persist($mouvement);
        $this->em->flush();
    }
}
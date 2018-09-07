<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Compte
 *
 * @ORM\Table(name="compte")
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 */
class Compte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="solde_initial", type="string", length=255, nullable=false)
     */
    private $soldeInitial;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="bigint", nullable=false)
     */
    private $ordre;

    /**
     * @ORM\OneToMany(targetEntity="Mouvement", mappedBy="compte", cascade={"persist"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    private $mouvements;

    /**
     * Compte constructor.
     */
    public function __construct()
    {
        $this->mouvements = new ArrayCollection();
    }

    /**
     * @return null|string
     */
    public function __toString()
    {
        return $this->getNom();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return $this
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getSoldeInitial(): ?string
    {
        return $this->soldeInitial;
    }

    /**
     * @param string $soldeInitial
     * @return $this
     */
    public function setSoldeInitial(string $soldeInitial): self
    {
        $this->soldeInitial = $soldeInitial;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return $this
     */
    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Add mouvements
     *
     * @param Mouvement $mouvement
     * @return $this
     */
    public function addMouvement(Mouvement $mouvement)
    {
        if (!$this->mouvements->contains($mouvement)) {
            $mouvement->setCompte($this);
            $this->mouvements->add($mouvement);
        }

        return $this;
    }

    /**
     * Remove mouvement
     *
     * @param Mouvement $mouvement
     * @return $this
     */
    public function removeMouvement(Mouvement $mouvement)
    {
        $this->mouvements->removeElement($mouvement);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMouvements()
    {
        return $this->mouvements;
    }

    /**
     * @param ArrayCollection $mouvements
     */
    public function setMouvements(ArrayCollection $mouvements)
    {
        foreach ($mouvements as $mouvement) {
            $mouvement->setCompte($this);
        }
        $this->mouvements = $mouvements;
    }
}

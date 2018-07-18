<?php

namespace App\Entity;

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
     * @return Compte
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
     * @return Compte
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
     * @return Compte
     */
    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }
}

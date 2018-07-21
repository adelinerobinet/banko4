<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MouvementAutomatique
 *
 * @ORM\Table(name="mouvement_automatique", indexes={@ORM\Index(name="IDX_9646EA2DF2C56620", columns={"compte_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\MouvementAutomatiqueRepository")
 */
class MouvementAutomatique
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
     * @var bool|null
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_jour", type="smallint", nullable=false)
     */
    private $numeroJour;

    /**
     * @var string
     *
     * @ORM\Column(name="credit", type="string", length=255, nullable=false)
     */
    private $credit;

    /**
     * @var string
     *
     * @ORM\Column(name="debit", type="string", length=255, nullable=false)
     */
    private $debit;

    /**
     * @var Compte
     *
     * @ORM\ManyToOne(targetEntity="Compte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="compte_id", referencedColumnName="id")
     * })
     */
    private $compte;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return bool|null
     */
    public function getActif(): ?bool
    {
        return $this->actif;
    }

    /**
     * @param bool|null $actif
     * @return MouvementAutomatique
     */
    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return MouvementAutomatique
     */
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumeroJour(): ?int
    {
        return $this->numeroJour;
    }

    /**
     * @param int $numeroJour
     * @return MouvementAutomatique
     */
    public function setNumeroJour(int $numeroJour): self
    {
        $this->numeroJour = $numeroJour;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCredit(): ?string
    {
        return $this->credit;
    }

    /**
     * @param string $credit
     * @return MouvementAutomatique
     */
    public function setCredit(string $credit): self
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getDebit(): ?string
    {
        return $this->debit;
    }

    /**
     * @param string $debit
     * @return MouvementAutomatique
     */
    public function setDebit(string $debit): self
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * @return Compte|null
     */
    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    /**
     * @param Compte|null $compte
     * @return MouvementAutomatique
     */
    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }


}

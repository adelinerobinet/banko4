<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mouvement
 *
 * @ORM\Table(name="mouvement", indexes={@ORM\Index(name="IDX_D9A07E9DF2C56620", columns={"compte_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\MouvementRepository")
 */
class Mouvement
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
     * @ORM\Column(name="traite", type="boolean", nullable=true)
     */
    private $traite;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
     */
    private $libelle;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="credit", type="string", length=255, nullable=true)
     */
    private $credit = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="debit", type="string", length=255, nullable=true)
     */
    private $debit = '0';

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
    public function getTraite(): ?bool
    {
        return $this->traite;
    }

    /**
     * @param bool|null $traite
     * @return Mouvement
     */
    public function setTraite(?bool $traite): self
    {
        $this->traite = $traite;

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
     * @return Mouvement
     */
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @param \DateTimeInterface|null $date
     * @return Mouvement
     */
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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
     * @param null|string $credit
     * @return Mouvement
     */
    public function setCredit(?string $credit): self
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
     * @param null|string $debit
     * @return Mouvement
     */
    public function setDebit(?string $debit): self
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
     * @return Mouvement
     */
    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }
}

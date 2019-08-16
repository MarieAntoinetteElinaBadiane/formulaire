<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomenvoi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomenvoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephoneenvoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $CNIenvoi;

    /**
     * @ORM\Column(type="date")
     */
    private $dateenvoi;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomretrai;


    /**
     * @ORM\Column(type="integer")
     */
    private $codeenvoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantenvoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionEtat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="usertrans")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionAdmin;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionEnvoi;

    /**
     * @ORM\Column(type="integer")
     */
    private $commissionRetrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateretrai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephoneretrai;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $CNIretrai;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomenvoi(): ?string
    {
        return $this->nomenvoi;
    }

    public function setNomenvoi(string $nomenvoi): self
    {
        $this->nomenvoi = $nomenvoi;

        return $this;
    }

    public function getPrenomenvoi(): ?string
    {
        return $this->prenomenvoi;
    }

    public function setPrenomenvoi(string $prenomenvoi): self
    {
        $this->prenomenvoi = $prenomenvoi;

        return $this;
    }

    public function getTelephoneenvoi(): ?int
    {
        return $this->telephoneenvoi;
    }

    public function setTelephoneenvoi(int $telephoneenvoi): self
    {
        $this->telephoneenvoi = $telephoneenvoi;

        return $this;
    }

    public function getCNIenvoi(): ?int
    {
        return $this->CNIenvoi;
    }

    public function setCNIenvoi(int $CNIenvoi): self
    {
        $this->CNIenvoi = $CNIenvoi;

        return $this;
    }

    public function getDateenvoi(): ?\DateTimeInterface
    {
        return $this->dateenvoi;
    }

    public function setDateenvoi(\DateTimeInterface $dateenvoi): self
    {
        $this->dateenvoi = $dateenvoi;

        return $this;
    }

    public function getNomretrai(): ?string
    {
        return $this->nomretrai;
    }

    public function setNomretrai(string $nomretrai): self
    {
        $this->nomretrai = $nomretrai;

        return $this;
    }

    public function getCodeenvoi(): ?int
    {
        return $this->codeenvoi;
    }

    public function setCodeenvoi(int $codeenvoi): self
    {
        $this->codeenvoi = $codeenvoi;

        return $this;
    }

    public function getMontantenvoi(): ?int
    {
        return $this->montantenvoi;
    }

    public function setMontantenvoi(int $montantenvoi): self
    {
        $this->montantenvoi = $montantenvoi;

        return $this;
    }

    public function getCommissionEtat(): ?int
    {
        return $this->commissionEtat;
    }

    public function setCommissionEtat(int $commissionEtat): self
    {
        $this->commissionEtat = $commissionEtat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommissionAdmin(): ?int
    {
        return $this->commissionAdmin;
    }

    public function setCommissionAdmin(int $commissionAdmin): self
    {
        $this->commissionAdmin = $commissionAdmin;

        return $this;
    }

    public function getCommissionEnvoi(): ?int
    {
        return $this->commissionEnvoi;
    }

    public function setCommissionEnvoi(int $commissionEnvoi): self
    {
        $this->commissionEnvoi = $commissionEnvoi;

        return $this;
    }

    public function getCommissionRetrait(): ?int
    {
        return $this->commissionRetrait;
    }

    public function setCommissionRetrait(int $commissionRetrait): self
    {
        $this->commissionRetrait = $commissionRetrait;

        return $this;
    }

    public function getDateretrai(): ?\DateTimeInterface
    {
        return $this->dateretrai;
    }

    public function setDateretrai(?\DateTimeInterface $dateretrai): self
    {
        $this->dateretrai = $dateretrai;

        return $this;
    }

    public function getTelephoneretrai(): ?int
    {
        return $this->telephoneretrai;
    }

    public function setTelephoneretrai(?int $telephoneretrai): self
    {
        $this->telephoneretrai = $telephoneretrai;

        return $this;
    }

    public function getCNIretrai(): ?int
    {
        return $this->CNIretrai;
    }

    public function setCNIretrai(?int $CNIretrai): self
    {
        $this->CNIretrai = $CNIretrai;

        return $this;
    }
}

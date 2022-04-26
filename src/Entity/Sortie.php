<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom_sortie;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description_sortie;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $motif_Annulation;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $photo_sortie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSortie(): ?string
    {
        return $this->nom_sortie;
    }

    public function setNomSortie(string $nom_sortie): self
    {
        $this->nom_sortie = $nom_sortie;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getDescriptionSortie(): ?string
    {
        return $this->description_sortie;
    }

    public function setDescriptionSortie(?string $description_sortie): self
    {
        $this->description_sortie = $description_sortie;

        return $this;
    }

    public function getMotifAnnulation(): ?string
    {
        return $this->motif_Annulation;
    }

    public function setMotifAnnulation(?string $motif_Annulation): self
    {
        $this->motif_Annulation = $motif_Annulation;

        return $this;
    }

    public function getPhotoSortie(): ?string
    {
        return $this->photo_sortie;
    }

    public function setPhotoSortie(?string $photo_sortie): self
    {
        $this->photo_sortie = $photo_sortie;

        return $this;
    }
}

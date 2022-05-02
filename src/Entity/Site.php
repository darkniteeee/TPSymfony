<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @ORM\Table(name="sites") *
 * @UniqueEntity(fields={"nom_site"}, message="Ce site existe déjà !")
 */
class Site
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name ="id", type="integer", options={"unsigned": true})
     */
    private ?int $id;

    /**
     * @Assert\NotBlank(message="Le nom du site est requis !")
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "Le nom du site doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le nom du site doit contenir au maximum {{ limit }} caractères !")
     *     @ORM\Column(name ="nom_site", type="string", length=30, unique=true)
     */
    private ?string $nom_site;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="site_organisateur")
     */
    private $sorties;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="site_id")
     */
    private $participants;



    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    #Mise en place des getters setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSite(): ?string
    {
        return $this->nom_site;
    }

    public function setNomSite(string $nom_site): void
    {
        $this->nom_site = $nom_site;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->setSiteOrganisateur($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getSiteOrganisateur() === $this) {
                $sortie->setSiteOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setSiteId($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getSiteId() === $this) {
                $participant->setSiteId(null);
            }
        }

        return $this;
    }
}

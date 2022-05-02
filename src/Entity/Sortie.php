<?php

namespace App\Entity;


use App\Repository\SortieRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 * @ORM\Table(name="sorties")
 *
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue (strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Le nom de la sortie est requis !")
     * @Assert\Length(
     *     min=3,
     *     max=30,
     *     minMessage = "Le nom de la sortie doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le nom de la sortie doit contenir au maximum {{ limit }} caractères !"
     * )
     *
     * @ORM\Column(name="nom_sortie", type="string", length=30)
     *
     */
    private ?string $nom_sortie = null;

    /**
     * @Assert\NotBlank(message="La date est requise !")
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private Datetime $date_debut;

    /**
     * @ORM\Column(name="duree", type="integer", nullable=true, options={"unsigned": true})
     */
    private int $duree;

    /**
     * @Assert\NotBlank(message="La date limite d'inscription est requise !")
     * @ORM\Column(name="date_limite_inscription", type="datetime")
     */
    private Datetime $date_limite_inscription;

    /**
     * @Assert\NotBlank(message="Le nombre limite de participants est requis !")
     * @ORM\Column(name="nb_inscription_max", type="integer", options={"unsigned": true})
     */
    private ?int $nb_inscription_max = null;

    /**
     * @ORM\Column(name="description_sortie", type="string", length=500, nullable=true)
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "La description de la sortie doit contenir au maximum {{ limit }} caractères !")
     */
    private string $description_sortie;

    /**
     * @Assert\Length(
     *     min = 10,
     *     max = 500,
     *     minMessage = "Le motif d'annulation doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le motif d'annulation doit contenir au maximum {{ limit }} caractères !")
     * @ORM\Column(name="motif_annulation", type="string", length=500, nullable=true)
     */
    private ?string $motif_annulation = null;

    /**
     * @ORM\Column(name="photo_sortie", type="string", length=250, nullable=true)
     */
    private string $photo_sortie;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site_organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Participant::class, inversedBy="organise")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Participant::class, mappedBy="inscriptions")
     */
    private $inscrits;


    ########Constructeur########
    public function __construct()
    {

        $this->participants = new ArrayCollection();
        $this->inscrits = new ArrayCollection();
    }


    ########  Mise en place des getters setters ###########
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNomSortie(): ?string
    {
        return $this->nom_sortie;
    }

    /**
     * @param string|null $nom_sortie
     */
    public function setNomSortie(?string $nom_sortie): void
    {
        $this->nom_sortie = $nom_sortie;
    }

    /**
     * @return DateTime
     */
    public function getDateDebut(): DateTime
    {
        return $this->date_debut;
    }

    /**
     * @param DateTime $date_debut
     */
    public function setDateDebut(DateTime $date_debut): void
    {
        $this->date_debut = $date_debut;
    }

    /**
     * @return int
     */
    public function getDuree(): int
    {
        return $this->duree;
    }

    /**
     * @param int $duree
     */
    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    /**
     * @return DateTime
     */
    public function getDateLimiteInscription(): DateTime
    {
        return $this->date_limite_inscription;
    }

    /**
     * @param DateTime $date_limite_inscription
     */
    public function setDateLimiteInscription(DateTime $date_limite_inscription): void
    {
        $this->date_limite_inscription = $date_limite_inscription;
    }

    /**
     * @return int|null
     */
    public function getNbInscriptionMax(): ?int
    {
        return $this->nb_inscription_max;
    }

    /**
     * @param int|null $nb_inscription_max
     */
    public function setNbInscriptionMax(?int $nb_inscription_max): void
    {
        $this->nb_inscription_max = $nb_inscription_max;
    }

    /**
     * @return string
     */
    public function getDescriptionSortie(): string
    {
        return $this->description_sortie;
    }

    /**
     * @param string $description_sortie
     */
    public function setDescriptionSortie(string $description_sortie): void
    {
        $this->description_sortie = $description_sortie;
    }

    /**
     * @return string
     */
    public function getMotifAnnulation(): string
    {
        return $this->motif_annulation;
    }

    /**
     * @param string $motif_annulation
     */
    public function setMotifAnnulation(string $motif_annulation): void
    {
        $this->motif_annulation = $motif_annulation;
    }

    /**
     * @return string
     */
    public function getPhotoSortie(): string
    {
        return $this->photo_sortie;
    }

    /**
     * @param string $photo_sortie
     */
    public function setPhotoSortie(string $photo_sortie): void
    {
        $this->photo_sortie = $photo_sortie;
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
            $participant->addInscription($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeInscription($this);
        }

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSiteOrganisateur(): ?Site
    {
        return $this->site_organisateur;
    }

    public function setSiteOrganisateur(?Site $site_organisateur): self
    {
        $this->site_organisateur = $site_organisateur;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getInscrits(): Collection
    {
        return $this->inscrits;
    }

    public function addInscrit(Participant $inscrit): self
    {
        if (!$this->inscrits->contains($inscrit)) {
            $this->inscrits[] = $inscrit;
            $inscrit->addInscription($this);
        }

        return $this;
    }

    public function removeInscrit(Participant $inscrit): self
    {
        if ($this->inscrits->removeElement($inscrit)) {
            $inscrit->removeInscription($this);
        }

        return $this;
    }



}

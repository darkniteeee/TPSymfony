<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 * @ORM\Table(name="etats")
 * @UniqueEntity(fields={"libelle"}, message="Le libelle existe déjà !")
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", options={"unsigned": true})
     */
    private ?int  $id =null;

    /**
     * @ORM\Column(name="libelle", type="string", length=30, unique=true)
     * @Assert\NotBlank(message="Le libelle est requis !")
     * @Assert\Length(
     *     min = 2,
     *     max = 30,
     *     minMessage = "Le libelle doit contenir au minimum {{ limit }} caractères !",
     *     maxMessage = "Le libelle doit contenir au maximum {{ limit }} caractères !",
     * )
     *
     */
    private ?string $libelle=null;


    #Mise en place en des getters setters
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
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }
}

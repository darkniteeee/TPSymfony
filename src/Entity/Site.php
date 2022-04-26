<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @ORM\Table(name="sites")
 *
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
     *
     * @ORM\Column(name ="nom_site", type="string", length=30)
     */
    private ?string $nom_site;

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
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Logement
 *
 * @ORM\Table(name="logement", indexes={@ORM\Index(name="FK_F0FD4457497DD634", columns={"categorie"})})
 * @ORM\Entity
 */
class Logement
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
     * @ORM\Column(name="nom", type="string", length=250, nullable=false)
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'le nom ne contient pas des nombre',
        match: true
    )]
    #[groups ("post:read")]
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250, nullable=false)
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'le nom ne contient pas des nombre',
        match: true
    )]
    #[groups ("post:read")]
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="place", type="integer", nullable=false)
     */
    private $place;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=false)
     */
   
    private $prix;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     * })
     */
    
    private $categorie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }


}

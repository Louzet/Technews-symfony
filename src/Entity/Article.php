<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un titre")
     * @Assert\Length(max="255",
     *     maxMessage="Votre titre ne peut excéder {{ limit }} charactères"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir un contenu")
     *
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(
     *     mimeTypesMessage="Vérifiez le format de votre image",
     *     maxSize="2M",
     *     maxSizeMessage="Image Trop volumineuse"
     * )
     */
    private $featuredImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $special;

    /**
     * @ORM\Column(type="boolean")
     */
    private $spotlight;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(message="Date Invalide")
     */
    private $datecreation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie",
     *     inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Vous devez choisir une catégorie")
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Membre",
     *     inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $status;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->datecreation = new \DateTime();
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
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     * @return Article
     */
    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Article
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param string $featuredImage
     * @return void
     */
    public function setFeaturedImage($featuredImage): void
    {
        $this->featuredImage = $featuredImage;
    }

    /**
     * @return bool|null
     */
    public function getSpecial(): ?bool
    {
        return $this->special;
    }

    /**
     * @param bool $special
     * @return Article
     */
    public function setSpecial(bool $special): self
    {
        $this->special = $special;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSpotlight(): ?bool
    {
        return $this->spotlight;
    }

    /**
     * @param bool $spotlight
     * @return Article
     */
    public function setSpotlight(bool $spotlight): self
    {
        $this->spotlight = $spotlight;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return string
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    /**
     * @param \DateTimeInterface $datecreation
     * @return Article
     */
    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    /**
     * @return Categorie|null
     */
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    /**
     * @param Categorie|null $categorie
     * @return Article
     */
    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Membre|null
     */
    public function getMembre(): ?Membre
    {
        return $this->membre;
    }

    /**
     * @param Membre|null $membre
     * @return Article
     */
    public function setMembre(?Membre $membre): self
    {
        $this->membre = $membre;

        return $this;
    }

    /**
     * Verifie si un membre est bien l'auteur
     * d'un article
     * @param Membre|null $membre
     * @return bool
     */
    public function isAuteur(Membre $membre = null): bool
    {
        return $membre && $this->getMembre()->getId() === $membre->getId();
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembreRepository")
 * @UniqueEntity(fields={"email"},
 *     errorPath="email",
 *     message="Ce email est déjà utilisé"
 * )
 */
class Membre implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Vous devez saisir votre prénom")
     * @Assert\Length(max="50", maxMessage="Votre prénom est trop long, {{ limit }} caractères max.")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Vous devez saisir votre nom")
     * @Assert\Length(max="50", maxMessage="Votre nom est trop long, {{ limit }} caractères max.")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank(message="Veuillez entrer votre email")
     * @Assert\Length(max="80", maxMessage="Votre email semble trop long, {{ limit }} caractères max.")
     * @Assert\Email(message="Verifiez le format de votre email")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Votre mot de passe est requis")
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]+$/",
     *     message="Votre mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre")
     * @Assert\Length(
     *     min="8",
     *     minMessage="Votre mot de passe est trop court",
     *     max="20",
     *     maxMessage="Votre mot de passe est trop long"
     *     )
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscription;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $derniereConnexion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article",
     *     mappedBy="membre")
     */
    private $articles;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var boolean
     * @Assert\IsTrue(message="Vous devez accepter les CGU")
     */
    private $conditions;

    /**
     * Membre constructor.
     * @param string $role
     */
    public function __construct(string $role = "ROLE_MEMBRE")
    {
        $this->addRole($role);
        $this->articles = new ArrayCollection();
        $this->dateInscription = new \DateTime("Europe/Paris");
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
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     * @return Membre
     */
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return Membre
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Membre
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return Membre
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    /**
     * @param \DateTimeInterface $dateInscription
     * @return Membre
     */
    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->derniereConnexion;
    }

    /**
     * @param string $timestamp
     * @return Membre
     */
    public function setDerniereConnexion(string $timestamp = 'now'): self
    {
        $this->derniereConnexion = new \DateTime($timestamp);

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * Returns the roles granted to the user.
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return array|null $array The user roles
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param mixed
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @param string $role
     * @return bool
     */
    public function addRole(string $role):bool
    {
        if(!in_array($role, $this->roles)){
            $this->roles[] = $role;
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions): void
    {
        $this->conditions = $conditions;
    }

    /* public function addArticle(Article $article): self
     {
         if (!$this->articles->contains($article)) {
             $this->articles[] = $article;
             $article->setMembre($this);
         }

         return $this;
     }

     public function removeArticle(Article $article): self
     {
         if ($this->articles->contains($article)) {
             $this->articles->removeElement($article);
             // set the owning side to null (unless already changed)
             if ($article->getMembre() === $this) {
                 $article->setMembre(null);
             }
         }

         return $this;
     }*/
    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password,
            $this->dateInscription,
            $this->derniereConnexion,
            $this->articles,
            $this->roles,
            $this->conditions
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return mixed
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->nom,
            $this->prenom,
            $this->email,
            $this->password,
            $this->dateInscription,
            $this->derniereConnexion,
            $this->articles,
            $this->roles,
            $this->conditions
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}

<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields="email", message="There is already an account with this email")
 * @UniqueEntity(fields="username", message="This username is already taken")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=50,  unique=true, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="user", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Recipes::class, mappedBy="user")
     */
    private $recipes;

    /**
     * @ORM\ManyToMany(targetEntity=Recipes::class)
     * @ORM\JoinTable(name="user_likes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="recipe_id", referencedColumnName="id")}
     * )
     */
    private $likedRecipes;

    public function __construct() {
        $this->roles = ['ROLE_USER'];
        $this->comments = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->likedRecipes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername(); // return username
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     * @return string the hashed password for this user
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }

    //MARIKA I ADDED : https://symfonycasts.com/screencast/symfony-security/password-credentials#play
    //The key thing is that this property will not be persisted to the database:
    // it's just a temporary property that we can use during, for example, registration, to store the plain password.
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * This method generates the URI for the user's avatar using the ui-avatars.com service.
     * It allows specifying the display name and avatar size.
     *
     * @param int $size The size of the avatar image (default is 35).
     *
     * @return string The URI for the user's avatar image.
     */
    public function getAvatarUrl(int $size = 35): string
    {
        return 'https://ui-avatars.com/api/?' . http_build_query([
                'name' => $this->getDisplayName(),
                'size' => $size,
        ]);
    }

    public function getDisplayName(): string
    {
        return $this->username ?? $this->getEmail();
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipes>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipes $recipe): self
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes[] = $recipe;
            $recipe->setUser($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getUser() === $this) {
                $recipe->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipes>
     */
    public function getLikedRecipes(): Collection
    {
        return $this->likedRecipes;
    }

    public function addLikedRecipe(Recipes $likedRecipe): self
    {
        if (!$this->likedRecipes->contains($likedRecipe)) {
            $this->likedRecipes[] = $likedRecipe;
        }

        return $this;
    }

    public function removeLikedRecipe(Recipes $likedRecipe): self
    {
        $this->likedRecipes->removeElement($likedRecipe);

        return $this;
    }
}
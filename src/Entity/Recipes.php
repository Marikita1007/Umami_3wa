<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo; // MARIKA TODO This is for created_at updated_at

/**
 * @ORM\Entity(repositoryClass=RecipesRepository::class)
 */
class Recipes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Recipe name must be at least {{ limit }} characters long",
     * )
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 5,
     *      max = 10000,
     *      minMessage = "Recipe description must be at least {{ limit }} characters long",
     *      maxMessage = "Recipe description cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 5,
     *      max = 10000,
     *      minMessage = "Recipe description must be at least {{ limit }} characters long",
     *      maxMessage = "Recipe description cannot be longer than {{ limit }} characters"
     * )
     */
    private $instructions;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Recipe image can not be blank.")
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prep_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $servings;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Cook time can not be blank.")
     * @Assert\Length(
     *      min = 2,
     *      minMessage = "Cook time must be at least {{ limit }} minutes long",
     * )
     */
    private $cook_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $calories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Difficulty")
     * @ORM\JoinColumn(name="difficulty_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank(message="Difficulty of the recipe can not be blank.")
     */
    private $difficulty;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Ingredients::class, mappedBy="recipe", orphanRemoval=true)
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName(); // return recipe name
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInstructions(): ?string
    {
        return $this->instructions;
    }

    public function setInstructions(?string $instructions): self
    {
        $this->instructions = $instructions;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrepTime(): ?int
    {
        return $this->prep_time;
    }

    public function setPrepTime(?int $prep_time): self
    {
        $this->prep_time = $prep_time;

        return $this;
    }

    public function getServings(): ?int
    {
        return $this->servings;
    }

    public function setServings(?int $servings): self
    {
        $this->servings = $servings;

        return $this;
    }

    public function getCookTime(): ?int
    {
        return $this->cook_time;
    }

    public function setCookTime(?int $cook_time): self
    {
        $this->cook_time = $cook_time;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get the User associated with this Recipe.
     *
     * @return User|null The User associated with this Recipe.
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the User associated with this Recipe.
     *
     * @param User|null $user The User to associate with this Recipe.
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecipe($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecipe() === $this) {
                $ingredient->setRecipe(null);
            }
        }

        return $this;
    }

}

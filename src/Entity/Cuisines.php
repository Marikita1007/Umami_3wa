<?php

namespace App\Entity;

use App\Repository\CuisinesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CuisinesRepository::class)
 * @UniqueEntity(fields="name", message="This Cuisine name already exist")
 */
class Cuisines
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Cuisine name is required")
     * @Assert\Length(
     *     min=2,
     *     max=30,
     *     minMessage="Cuisine name must be at least {{ limit }} characters long",
     *     maxMessage="Cuisine name cannot be longer than {{ limit }} characters")
     * @Assert\Regex(
     *      pattern = "/^[a-zA-Z0-9\s]+$/",
     *      message = "Cuisine name can only contain letters, numbers, and spaces."
     * )
     * @ORM\Column(type="string", length=30, unique=true, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Recipes::class, mappedBy="cuisine")
     */
    private $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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
            $recipe->setCuisine($this);
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): self
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getCuisine() === $this) {
                $recipe->setCuisine(null);
            }
        }

        return $this;
    }
}

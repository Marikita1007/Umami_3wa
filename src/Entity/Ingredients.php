<?php

namespace App\Entity;

use App\Repository\IngredientsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=IngredientsRepository::class)
 */
class Ingredients
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank(message="Ingredient name is required")
     * @Assert\Length(
     *     min=2,
     *     max=35,
     *     minMessage="Ingredient name must be at least {{ limit }} characters long",
     *     maxMessage="Ingredient name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *      pattern="/^[\p{L}0-9\s!&.:]+$/u",
     *      message="Ingredient name can only contain letters, numbers, and spaces."
     * )
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Recipes::class, inversedBy="ingredients", cascade={"persist"})
     * @ORM\JoinColumn(name="recipe_id", nullable=false)
     */
    private $recipe;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ingredient amount is required")
     * @Assert\Length(
     *     min=1,
     *     max=100,
     *     minMessage="Amount must not be empty",
     *     maxMessage="Amount cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *      pattern="/^[\p{L}0-9\s!&.:]+$/u",
     *      message="Ingredient amount can only contain letters, numbers, and spaces."
     * )
     */
    private $amount;

    public function __toString(): string
    {
        return $this->getId();
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

    public function getRecipe(): ?Recipes
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipes $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}

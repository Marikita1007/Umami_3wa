<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 * @UniqueEntity(fields="name", message="This Cuisine name already exist")
 */
class Categories
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Choose at least one category")
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Recipes::class, inversedBy="category")
     */
    private $recipe;


    public function __construct()
    {
        $this->recipe = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName(); // return category name
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
    public function getRecipe(): Collection
    {
        return $this->recipe;
    }

    public function addRecipe(Recipes $recipe): self
    {
        if (!$this->recipe->contains($recipe)) {
            $this->recipe[] = $recipe;
        }

        return $this;
    }

    public function removeRecipe(Recipes $recipe): self
    {
        $this->recipe->removeElement($recipe);

        return $this;
    }

}

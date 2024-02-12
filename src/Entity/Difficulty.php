<?php

namespace App\Entity;

use App\Repository\DifficultyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DifficultyRepository::class)
 */
class Difficulty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Difficulty must be at least {{ limit }} characters long",
     *      maxMessage = "Difficultycannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *      pattern = "/^[\p{L}]+$/u",
     *      message = "Difficulty can only contain letters and spaces."
     * )
     * @Assert\NotBlank(message="Instructions can not be blank.")
     * @ORM\Column(type="string", length=50)
     */
    private $name;

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

    public function __toString()
    {
        return $this->getName();
    }

}

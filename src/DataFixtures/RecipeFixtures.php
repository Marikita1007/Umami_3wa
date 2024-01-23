<?php

namespace App\DataFixtures;

use App\Entity\Difficulty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Recipes;
use Faker\Factory;
use Faker\Generator;

// Implemented the 'FixtureGroupInterface' for specifying a fixture group
class RecipeFixtures extends Fixture implements FixtureGroupInterface
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        // Create 20 recipes with faker
        for ($i = 0; $i < 20; $i++) {
            $recipe = new Recipes();
            $recipe->setName($this->faker->sentence);
            $recipe->setDescription($this->faker->paragraph);
            $recipe->setInstructions($this->faker->text);
            $recipe->setCreatedAt($this->faker->dateTimeThisYear);
            $recipe->setThumbnail("salad_fig.jpg");
            $recipe->setPrepTime($this->faker->numberBetween(1,120));
            $recipe->setServings($this->faker->numberBetween(1, 10));
            $recipe->setCookTime($this->faker->numberBetween(1,120));
            $recipe->setCalories($this->faker->numberBetween(100, 1000));

            $difficultyId = $this->faker->numberBetween(1, 3); // random difficulty id
            $difficulty = $manager->getRepository(Difficulty::class)->find($difficultyId);
            $recipe->setDifficulty($difficulty);

            $manager->persist($recipe);
        }

        $manager->flush();
    }


    public static function getGroups(): array
    {
        return ['recipes']; // Specify the group name
    }

}

<?php

namespace App\Command;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

// the "name" and "description" arguments of AsCommand replace the
// static $defaultName and $defaultDescription properties
// todo Marika Write in README php bin/console DefaultCategoriesCommand to Update default Categories

/**
 * This Symfony Console command, DefaultCategoriesCommand, is responsible for creating default categories
 * if they do not already exist in the database. It checks for existing categories and adds the default ones
 * if none are found.
 */
#[AsCommand(
    name: 'DefaultCategoriesCommand',
    description: 'Create default categories',
    aliases: ['dccreate']
)]
class DefaultCategoriesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    /**
     * DefaultCategoriesCommand constructor.
     * @param EntityManagerInterface $entityManager The entity manager for interacting with the database.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    /**
     * The main execution method of the command.
     *
     * @param InputInterface $input The input interface for accessing command-line arguments.
     * @param OutputInterface $output The output interface for displaying information.
     *
     * @return int The command execution status.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Check if there are existing categories
        $existingCategories = $this->entityManager->getRepository(Categories::class)->findAll();

        if (empty($existingCategories)) {
            // If no categories exist, add default categories
            $defaultCategories = [
                'Vegetarian',
                'Gluten-Free',
                'Quick and Easy',
                'Keto-friendly',
                'Plant-Based',
                'Dairy-Free',
                'Low-Carb',
                'Comfort Food',
                'Paleo-friendly',
                'Breakfast',
                'Lunch',
                'Dinner',
                'Snack',
                'Soup',
                'Salad',
                'Dessert',
                'Beverage',
                'Grilling',
                'Holiday Specials',
                'One-Pot Meals',
                'Slow Cooker Recipes',
                'Party',
            ];

            // Iterate through default categories and persist them to the database
            foreach ($defaultCategories as $categoryName) {
                $category = new Categories();
                $category->setName($categoryName);

                $this->entityManager->persist($category);
            }

            // Flush changes to the database
            $this->entityManager->flush();
            $output->writeln('Default categories added.');
        } else {
            // If categories already exist, skip the addition process
            $output->writeln('Categories already exist, skipping.');
        }

        // Return the command execution status
        return Command::SUCCESS;
    }
}

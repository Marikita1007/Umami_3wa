<?php

namespace App\DataFixtures;
use App\Factory\UsersFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class UserFixtures extends Fixture
{
    //MARIKA TODO : Write README about Factory and Fixtures
    public function load(ObjectManager $manager)
    {
        // Create test user admin with infos down below
        UsersFactory::createOne([
            'email' => 'umami_admin@example.com',
            'roles' => ['ROLE_ADMIN'],
            'username' => 'admin'
        ]);
        // Create test user with ROLE_USER (default)
        UsersFactory::createOne([
            'email' => 'umami_user@example.com',
            'roles' => ['ROLE_USER'], // Set status to ROLE_USER
            'username' => 'user1'
        ]);

        // Create 10 more users with ROLE_USER (default)
        UsersFactory::createMany(10, function() {
            return [
                'roles' => ['ROLE_USER'] // Set status to ROLE_USER
            ];
        });
        $manager->flush();
    }
}
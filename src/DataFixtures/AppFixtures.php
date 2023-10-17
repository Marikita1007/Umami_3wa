<?php

namespace App\DataFixtures;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class UserFixtures extends Fixture
{
    //MARIKA TODO : Write README about FActory and Fixtures
    public function load(ObjectManager $manager)
    {
<<<<<<< Updated upstream:src/DataFixtures/AppFixtures.php
        UserFactory::createOne([
            'email' => 'abraca_admin@example.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'email' => 'abraca_user@example.com',
        ]);
        UserFactory::createMany(10);
=======
        // To create test admin user, only this user will be registered as an admin
        UsersFactory::createOne([
            'email' => 'umami_admin@example.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        // To create test user, this user will be registered as a user
        UsersFactory::createOne([
            'email' => 'umami_user@example.com',
        ]);
        // Create 10 test users
        UsersFactory::createMany(10);
>>>>>>> Stashed changes:src/DataFixtures/UserFixtures.php
        $manager->flush();
    }
}
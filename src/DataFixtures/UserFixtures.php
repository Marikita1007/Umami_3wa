<?php

namespace App\DataFixtures;
use App\Factory\UsersFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class UserFixtures extends Fixture
{
    //MARIKA TODO : Write README about FActory and Fixtures
    public function load(ObjectManager $manager)
    {
        // Create test user admin with infos down below
        UsersFactory::createOne([
            'email' => 'umami_admin@example.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        // Create test user with infos down below
        UsersFactory::createOne([
            'email' => 'umami_user@example.com',
        ]);
        UsersFactory::createMany(10);
        $manager->flush();
    }
}
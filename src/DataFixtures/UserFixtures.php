<?php

namespace App\DataFixtures;
use App\Factory\UsersFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UsersFactory::createOne([
            'email' => 'abraca_admin@example.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        UsersFactory::createOne([
            'email' => 'abraca_user@example.com',
        ]);
        UsersFactory::createMany(10);
        $manager->flush();
    }
}
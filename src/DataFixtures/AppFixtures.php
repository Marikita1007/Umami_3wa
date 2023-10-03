<?php

namespace App\DataFixtures;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::createOne([
            'email' => 'abraca_admin@example.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'email' => 'abraca_user@example.com',
        ]);
        UserFactory::createMany(10);
        $manager->flush();
    }
}
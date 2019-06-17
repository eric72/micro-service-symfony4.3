<?php

namespace App\DataFixtures;

use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstname('jean');
        $user->setLastname('bon');
        $user->setCreationdate(new DateTime("now"));
        $user->setUpdatedate(new DateTime("now"));
        $manager->persist($user);

        $manager->flush();
    }
}

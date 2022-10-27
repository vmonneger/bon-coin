<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $firstTag = new Tags();
        $sndTag = new Tags();
        $trdTag = new Tags();

        $firstTag->setName("Immobilier");
        $sndTag->setName("Vacance");
        $trdTag->setName("Vehicule");

        $manager->persist($firstTag);
        $manager->persist($sndTag);
        $manager->persist($trdTag);

        $manager->flush();

    }
}

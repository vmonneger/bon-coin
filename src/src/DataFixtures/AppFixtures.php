<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $firstTag = new Tags();
        $sndTag = new Tags();
        $trdTag = new Tags();

        $user = new User();
        $password = $this->hasher->hashPassword($user, 'admin!');

        $user->setEmail('admin@admin.com')
            ->setName('admin')
            ->setPassword($password)
            ->setRoles(array('ROLE_ADMIN'))
            ->setCreated_At(new \DateTimeImmutable('now'));

        $firstTag->setName("Immobilier");
        $sndTag->setName("Vacance");
        $trdTag->setName("Vehicule");

        $manager->persist($firstTag);
        $manager->persist($sndTag);
        $manager->persist($trdTag);
        $manager->persist($user);

        $manager->flush();

    }
}

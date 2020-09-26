<?php

namespace App\DataFixtures;

use App\Entity\User\Email;
use App\Entity\User\Id;
use App\Entity\User\Name;
use App\Entity\User\User;
use App\Service\User\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var PasswordHasher
     */
    private PasswordHasher $passwordHasher;

    public function __construct(PasswordHasher $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $passwordHash = $this->passwordHasher->hash('password');

        for ($i = 0; $i < 10; $i++) {
            $user = new User(
                Id::generate(),
                new Name($faker->firstName, $faker->lastName),
                new Email($faker->email),
                $passwordHash
            );

            $manager->persist($user);
        }

        $manager->flush();
    }
}

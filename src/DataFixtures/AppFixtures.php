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
    private const USER_ID = '00000000-0000-0000-0000-000000000001';
    private const USER_EMAIL = 'user@app.test';
    private const USER_FIRST_NAME = 'Augustus';
    private const USER_LAST_NAME = 'Crooks';

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

        // User with static data
        $manager->persist(new User(
            new Id(self::USER_ID),
            new Name(self::USER_FIRST_NAME, self::USER_LAST_NAME),
            new Email(self::USER_EMAIL),
            $passwordHash
        ));

        // Random users
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

    public static function getUser(): array
    {
        return [
            'id' => self::USER_ID,
            'name' => [
                'first' => self::USER_FIRST_NAME,
                'last' => self::USER_LAST_NAME
            ],
            'email' => self::USER_EMAIL
        ];
    }
}

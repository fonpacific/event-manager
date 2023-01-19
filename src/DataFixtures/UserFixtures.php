<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private const NUMBER_OF_USERS_FAKER = 20;
    private const USER_DEFAULT_PASSWORD = 'testPwd666';

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= self::NUMBER_OF_USERS_FAKER; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                self::USER_DEFAULT_PASSWORD
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
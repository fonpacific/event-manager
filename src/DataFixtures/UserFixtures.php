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
    private const NUMBER_OF_ORGANIZERS_FAKER = 5;
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

        for ($i = 1; $i <= self::NUMBER_OF_ORGANIZERS_FAKER; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                self::USER_DEFAULT_PASSWORD
            ));
            $user->setRoles(['ROLE_ORGANIZER']);
            $manager->persist($user);
        }

        $user = new User();
        $user->setEmail('admin@eventmanager.com');
        $user->setPassword($this->userPasswordHasher->hashPassword(
            $user,
            '4dmin123'
        ));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user);

        $manager->flush();
    }
}
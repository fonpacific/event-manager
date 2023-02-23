<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use App\EventManager;
use App\Exception\UserIsAlreadyRegisteredToThisEventException;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function assert;
use function count;
use function rand;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private const NUMBER_OF_USERS_FAKER = 200;
    private const NUMBER_OF_ORGANIZERS_FAKER = 5;
    private const USER_DEFAULT_PASSWORD = 'testPwd666';
    private const USER_MAX_RANDOM_NUMBER = 20;

    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly EventManager $eventManager
    ) {
    }

    public function load(ObjectManager $manager): void
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

        $organizers = [];

        for ($i = 1; $i <= self::NUMBER_OF_ORGANIZERS_FAKER; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                self::USER_DEFAULT_PASSWORD
            ));
            $user->setRoles(['ROLE_ORGANIZER']);
            $manager->persist($user);
            $organizers[] = $user;
        }

        $adminUser = new User();
        $adminUser->setEmail('admin@eventmanager.com');
        $adminUser->setPassword($this->userPasswordHasher->hashPassword(
            $adminUser,
            '4dmin123'
        ));
        $adminUser->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($adminUser);
        $organizers[] = $adminUser;

        $manager->flush();

        $events = $manager->getRepository(Event::class)->findAll();
        foreach ($events as $event) {
            assert($event instanceof Event);
            $randomUsers = rand(0, self::USER_MAX_RANDOM_NUMBER);
            $event->setOrganizer($organizers[rand(0, count($organizers) - 1)]);
            for ($i = 0; $i < $randomUsers; $i++) {
                $user = AppFixtures::getRandomEntity($manager, User::class);
                try {
                    $this->eventManager->register($event, $user, false);
                } catch (UserIsAlreadyRegisteredToThisEventException $e) {
                    continue;
                }
            }
        }

        $manager->flush();
    }

    /** @return string[] */
    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }
}

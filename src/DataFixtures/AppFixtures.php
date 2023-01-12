<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Place;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private const NUMBER_OF_EVENTS_FAKER = 50;
    private const NUMBER_OF_PLACES_FAKER = 30;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= self::NUMBER_OF_EVENTS_FAKER; $i++) {
            $event = new Event();
            $event->setName($faker->words(3, true));
            $event->setDescription(rand(0,2) === 0 ? $faker->text(250) : null);
            $event->setMaxAttendeesNumber(rand(0,2) === 0 ? $faker->numberBetween(50, 1000) : null);
            $event->setStatus($faker->randomElement(Event::STATUSES));
            $event->setStartDate($faker->dateTimeThisYear('+2 months'));
            $event->setEndDate($event->getStartDate()->modify('+'.rand(1, 48).' hours'));
            $event->setRegistrationStartDate(rand(0, 2) === 0 ? $event->getStartDate()->modify('-'.rand(1, 168).' hours'): null);
            $event->setRegistrationEndDate(
                rand(0, 2) === 0 ?
                    (($event->getRegistrationStartDate() === null) ? $event->getStartDate()->modify('-'.rand(1, 168).' hours') : $faker->dateTimeBetween($event->getRegistrationStartDate()->getTimestamp(), $event->getStartDate()->getTimestamp())):
                    null
            );
            $manager->persist($event);
        }

        for ($i = 1; $i <= self::NUMBER_OF_PLACES_FAKER; $i++) {
            $place = new Place();
            $place->setName($faker->words(3, true));
            $place->setDescription(rand(0,2) === 0 ? $faker->text(250) : null);
            $manager->persist($place);
        }


        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}

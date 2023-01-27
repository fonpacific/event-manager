<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Intl\Countries;

class CountryFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $countries = Countries::getNames();

        foreach ($countries as $countryCode => $countriesName) {
            $country = new Country();
            $country->setName($countriesName);
            $country->setCode($countryCode);

            $manager->persist($country);
        }

        $manager->flush();
    }
}
<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Province;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\IntlSubdivision\IntlSubdivision;

use function array_keys;
use function utf8_encode;

class ProvinceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $countriesCodes = array_keys(Countries::getNames());

        foreach ($countriesCodes as $countryCode) {
            try {
                if ($countryCode === 'IT') {
                    $reader = new Xls();
                    $reader->setLoadSheetsOnly(['codici_province']);
                    $spreadsheet = $reader->load(__DIR__ . '/data/Elenco-codici-statistici-e-denominazioni-al-01_01_2020.xls');

                    $dataArray = $spreadsheet->getActiveSheet()
                        ->rangeToArray(
                            'A1:B107',     // The worksheet range that we want to retrieve
                            null,        // Value that should be returned for empty cells
                            false,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                            false,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                            false         // Should the array be indexed by cell row and cell column
                        );
                    $provinceCodes = [];
                    foreach ($dataArray as $data) {
                        $provinceCodes[$data[1]] = $data[0];
                    }
                } else {
                    $provinceCodes = IntlSubdivision::getStatesAndProvincesForCountry($countryCode);
                }
            } catch (MissingResourceException $exception) {
                continue;
            }

            foreach ($provinceCodes as $provinceCode => $provinceName) {
                $provinceCode = utf8_encode((string) $provinceCode);
                $provinceName = utf8_encode((string) $provinceName);
                $codeRepresentation = $countryCode . '_' . $provinceCode;

                $country = $manager->getRepository(Country::class)->findOneBy(['code' => $countryCode]);
                $existent = $manager->getRepository(Province::class)->findOneBy([
                    'country' => $country,
                    'name' => $provinceName,
                ]);

                if ($existent !== null) {
                    continue;
                }

                $province = new Province();
                $province->setCode($codeRepresentation);
                $province->setName($provinceName);
                $province->setCountry($country);

                $manager->persist($province);
                $manager->flush();
            }
        }
    }
}

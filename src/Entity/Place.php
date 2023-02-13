<?php

namespace App\Entity;

use App\Model\DescriptionTrait;
use App\Model\IdentifiableTrait;
use App\Model\NameTrait;
use App\Model\TimeStampableTrait;
use App\Repository\PlaceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class),ORM\HasLifecycleCallbacks]
class Place
{
    use TimeStampableTrait, IdentifiableTrait, NameTrait, DescriptionTrait;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $streetAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Embedded(class: GeoCoordinates::class)]
    private GeoCoordinates $coordinates;

    public function __construct() {
        $this->coordinates = new GeoCoordinates();
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(?string $streetAddress): void
    {
        $this->streetAddress = $streetAddress;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getCoordinates(): GeoCoordinates
    {
        return $this->coordinates;
    }

    public function setCoordinates(GeoCoordinates $coordinates): void
    {
        $this->coordinates = $coordinates;
    }
}

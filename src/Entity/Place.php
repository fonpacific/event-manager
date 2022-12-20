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

    public function setStreetAddress(?string $streetAddress): self
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return GeoCoordinates
     */
    public function getCoordinates(): GeoCoordinates
    {
        return $this->coordinates;
    }

    /**
     * @param GeoCoordinates $coordinates
     */
    public function setCoordinates(GeoCoordinates $coordinates): void
    {
        $this->coordinates = $coordinates;
    }


}

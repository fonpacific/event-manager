<?php

namespace App\Entity;

use App\Model\DescriptionTrait;
use App\Model\IdentifiableTrait;
use App\Model\NameTrait;
use App\Model\TimeStampableTrait;
use App\Repository\PlaceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class), ORM\HasLifecycleCallbacks]
class Place
{
    use TimeStampableTrait, IdentifiableTrait, NameTrait, DescriptionTrait;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $streetAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $City = null;

    #[ORM\Embedded(class: GeoCoordinates::class)]
    private GeoCoordinates $coordinates;

    private function __construct(){
        $this->coordinates = new GeoCoordinates();   
    }

    public function getStreetAddress(): ?string
    {
        return $this->streetAddress;
    }

    public function setStreetAddress(?string $streetAddress): static
    {
        $this->streetAddress = $streetAddress;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(?string $City): static
    {
        $this->City = $City;

        return $this;
    }

    

    /**
     * Get the value of coordinates
     */
    public function getCoordinates(): GeoCoordinates
    {
        return $this->coordinates;
    }

    /**
     * Set the value of coordinates
     */
    public function setCoordinates(GeoCoordinates $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }
}

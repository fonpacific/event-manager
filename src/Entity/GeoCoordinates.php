<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Embeddable]

class GeoCoordinates
{

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $latitude = null;
    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $longitude = null;

    


    /**
     * Get the value of latitude
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * Set the value of latitude
     */
    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get the value of longitude
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * Set the value of longitude
     */
    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}

<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait DescriptionTrait
{

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}

<?php

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

trait NameTrait
{

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __toString(): string
    {
        return $this->name ?? 'nd';
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

}
<?php

declare(strict_types=1);

namespace App\Model;

use App\Slugger;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait NameTrait
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank, Assert\Type('string'), Assert\Length(min: 5, max: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

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
        $this->slug = Slugger::slug($name);
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}

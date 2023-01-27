<?php

namespace App\Entity;

use App\Model\IdentifiableTrait;
use App\Model\NameTrait;
use App\Model\TimeStampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
class Province
{
    use IdentifiableTrait;
    use TimeStampableTrait;
    use NameTrait;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'provinces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): void
    {
        $this->country = $country;
    }
}
<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Safe\DateTime;

trait TimeStampableTrait
{
    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreationTimestampableValue(): void
    {
        $this->createdAt ??= new DateTime();
        $this->updatedAt ??= new DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdateTimestampableValue(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }
}

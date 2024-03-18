<?php

namespace App\Entity;

use App\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Model\TimeStampableTrait;
use App\Repository\RegistrationRepository;

#[ORM\Entity(repositoryClass: RegistrationRepository::class),  ORM\HasLifecycleCallbacks]
class Registration
{
    use TimeStampableTrait, IdentifiableTrait;

    public function __construct(Event $event, User $user) {
        $this->platformUser = $user;
        $this->event=$event;
    }


    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $platformUser = null;

    #[ORM\ManyToOne(inversedBy: 'registrations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Event $event = null;

    public function getPlatformUser(): ?User
    {
        return $this->platformUser;
    }

    public function setPlatformUser(?User $platformUser): static
    {
        $this->platformUser = $platformUser;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }
}

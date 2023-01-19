<?php

namespace App\Entity;

use App\Model\DescriptionTrait;
use App\Model\IdentifiableTrait;
use App\Model\NameTrait;
use App\Model\TimeStampableTrait;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class),ORM\HasLifecycleCallbacks]
class Event
{
    use TimeStampableTrait, IdentifiableTrait, NameTrait, DescriptionTrait;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUSES = [self::STATUS_CANCELLED, self::STATUS_DRAFT, self::STATUS_PUBLISHED];
    public const STATUSES_AVAILABLE = [self::STATUS_PUBLISHED, self::STATUS_CANCELLED];

    #[ORM\Column(nullable: true)]
    private ?int $maxAttendeesNumber = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationStartDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationEndDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $accessStartDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $accessEndDate = null;

    #[ORM\ManyToOne]
    private ?Place $place = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getMaxAttendeesNumber(): ?int
    {
        return $this->maxAttendeesNumber;
    }

    public function setMaxAttendeesNumber(?int $maxAttendeesNumber): void
    {
        $this->maxAttendeesNumber = $maxAttendeesNumber;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): void
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
    }

    public function removeChild(self $child): void
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getRegistrationStartDate(): ?\DateTimeInterface
    {
        return $this->registrationStartDate;
    }

    public function setRegistrationStartDate(?\DateTimeInterface $registrationStartDate): void
    {
        $this->registrationStartDate = $registrationStartDate;
    }

    public function getRegistrationEndDate(): ?\DateTimeInterface
    {
        return $this->registrationEndDate;
    }

    public function setRegistrationEndDate(?\DateTimeInterface $registrationEndDate): void
    {
        $this->registrationEndDate = $registrationEndDate;
    }

    public function getAccessStartDate(): ?\DateTimeInterface
    {
        return $this->accessStartDate;
    }

    public function setAccessStartDate(?\DateTimeInterface $accessStartDate): void
    {
        $this->accessStartDate = $accessStartDate;
    }

    public function getAccessEndDate(): ?\DateTimeInterface
    {
        return $this->accessEndDate;
    }

    public function setAccessEndDate(?\DateTimeInterface $accessEndDate): void
    {
        $this->accessEndDate = $accessEndDate;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): void
    {
        $this->place = $place;
    }
}

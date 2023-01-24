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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: EventRepository::class),ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Event
{
    use TimeStampableTrait, IdentifiableTrait, NameTrait, DescriptionTrait;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUSES = [self::STATUS_CANCELLED, self::STATUS_DRAFT, self::STATUS_PUBLISHED];
    public const STATUSES_AVAILABLE = [self::STATUS_PUBLISHED, self::STATUS_CANCELLED];

    public function canRegister(User $user): bool
    {
        if ($this->isRegistered($user))
        {
            return false;
        }

        if ($this->getMaxAttendeesNumber() !== null && $this->registrationCount() >= $this->getMaxAttendeesNumber())
        {
            return false;
        }

        $now = new \DateTime();
        if (
            ($this->getRegistrationStartDate() !== null && $now <= $this->getRegistrationStartDate()) ||
            ($this->getRegistrationEndDate() !== null && $now >= $this->getRegistrationEndDate())
        )
        {
            return false;
        }

        if ($this->status !== self::STATUS_PUBLISHED)
        {
            return false;
        }

        return true;
    }

    public function registrationCount(): int
    {
        return $this->getRegisteredUsers()->count();
    }

    public function isRegistered(User $user): bool
    {
           return $this->getRegisteredUsers()->contains($user);
    }

    public function getRegisteredUsers(): Collection
    {
        $users = [];
        foreach($this->registrations as $registration)
        {
            assert($registration instanceof Registration);
            $users[] = $registration->getPlatformUser();
        }

        return new ArrayCollection($users);
    }

    #[ORM\Column(nullable: true)]
    #[Assert\Type('integer'),Assert\GreaterThan(0)]
    private ?int $maxAttendeesNumber = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank, Assert\Type('string')]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Assert\Type('datetime'), Assert\GreaterThan(propertyPath: 'startDate')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $registrationStartDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Assert\Type('datetime'), Assert\GreaterThan(propertyPath: 'registrationStartDate')]
    private ?\DateTimeInterface $registrationEndDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $accessStartDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Assert\Type('datetime'), Assert\GreaterThan(propertyPath: 'accessStartDate')]
    private ?\DateTimeInterface $accessEndDate = null;

    #[ORM\ManyToOne]
    private ?Place $place = null;

    #[Vich\UploadableField(mapping: 'event_cover', fileNameProperty: 'coverImageName', originalName: 'coverImageOriginalName', size: 'coverImageSize')]
    private ?File $coverImageFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $coverImageName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $coverImageSize = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $coverImageOriginalName = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Registration::class)]
    private Collection $registrations;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->status = self::STATUS_DRAFT;
        $this->registrations = new ArrayCollection();
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

    /**
     * @return File|null
     */
    public function getCoverImageFile(): ?File
    {
        return $this->coverImageFile;
    }

    /**
     * @param File|null $coverImageFile
     */
    public function setCoverImageFile(?File $coverImageFile): void
    {
        $this->coverImageFile = $coverImageFile;
        if (null !== $coverImageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return string|null
     */
    public function getCoverImageName(): ?string
    {
        return $this->coverImageName;
    }

    /**
     * @param string|null $coverImageName
     */
    public function setCoverImageName(?string $coverImageName): void
    {
        $this->coverImageName = $coverImageName;
    }

    /**
     * @return int|null
     */
    public function getCoverImageSize(): ?int
    {
        return $this->coverImageSize;
    }

    /**
     * @param int|null $coverImageSize
     */
    public function setCoverImageSize(?int $coverImageSize): void
    {
        $this->coverImageSize = $coverImageSize;
    }

    /**
     * @return string|null
     */
    public function getCoverImageOriginalName(): ?string
    {
        return $this->coverImageOriginalName;
    }

    /**
     * @param string|null $coverImageOriginalName
     */
    public function setCoverImageOriginalName(?string $coverImageOriginalName): void
    {
        $this->coverImageOriginalName = $coverImageOriginalName;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): self
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setEvent($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): self
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getEvent() === $this) {
                $registration->setEvent(null);
            }
        }

        return $this;
    }


}

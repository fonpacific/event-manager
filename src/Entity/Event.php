<?php

namespace App\Entity;

use App\Model\NameTrait;
use Doctrine\DBAL\Types\Types;
use App\Model\DescriptionTrait;
use App\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Model\TimeStampableTrait;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;


use Doctrine\Common\Collections\ArrayCollection;
use Sonata\MediaBundle\Entity\BaseMedia;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EventRepository::class), ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Event
{

    use TimeStampableTrait, IdentifiableTrait, NameTrait, DescriptionTrait;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUSES = [self::STATUS_DRAFT, self::STATUS_PUBLISHED,self::STATUS_CANCELLED];
    public const STATUSES_AVAILABLE = [self::STATUS_PUBLISHED,self::STATUS_CANCELLED];
   
    public function canRegister(User $user): bool {
        if ($this->isRegistered($user)) {
            return false;
        } 

        if($this->getMaxAttendeesNumber() != null && $this->registrationCount() >= $this->getMaxAttendeesNumber()) {
            return false;
        }

        $now=new \DateTime();
        if (
            ($this->getRegistrationsStartDate() !== null && $now <= $this->getRegistrationsStartDate()) ||
            ($this->getRegistrationsEndDate2() !== null && $now >= $this->getRegistrationsEndDate2())
        ) {
            return false;
        }

        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        } 

        return true;        
    }

    public function registrationCount(): int {
        return $this->registrations->count();
    }


    public function isRegistered(User $user): bool {
        return $this->getRegisteredUsers()->contains($user);
    }

    public function getRegisteredUsers(): Collection {
        $users = [];

        foreach($this->registrations as $registration) {
            assert($registration instanceof Registration);
            $users[]= $registration->getPlatformUser();
        }

        return new ArrayCollection($users);
    }

    public function approve(): void {
        if($this->status !== self::STATUS_DRAFT) {
            return;
        }
        $this->status = self::STATUS_PUBLISHED;
    }

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Assert\Type('datetime'), Assert\NotNull]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Assert\Type('datetime'), Assert\NotNull, Assert\GreaterThan(propertyPath: 'startDate')]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('integer'), Assert\GreaterThan(0)]
    private ?int $maxAttendeesNumber = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $children;

    #[ORM\Column(length: 255)]
    #[assert\NotNull(), Assert\Type('string')]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Assert\Type('datetime')]
    private ?\DateTimeInterface $registrationsStartDate = null;


    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Assert\Type('datetime'), Assert\GreaterThan(propertyPath: 'registrationsStartDate')]
    private ?\DateTimeInterface $registrationsEndDate2 = null;

    #[ORM\ManyToOne]
    private ?Place $place = null;

    #[Vich\UploadableField(mapping: 'event_cover', fileNameProperty: 'coverImageName', originalName:'coverImageOriginalName', size: 'coverImageSize')]
    private ?File $coverImageFile = null;
    


    #[ORM\Column(nullable: true, type: 'string')]
    private ?string $coverImageName = null;

    #[ORM\Column(nullable: true, type: 'integer')]
    private ?int $coverImageSize = null;

    #[ORM\Column(nullable: true, type: 'string')]
    private ?string $coverImageOriginalName = null;

    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'event')]
    private Collection $registrations;

    #[ORM\ManyToOne(inversedBy: 'eventi')]
    private ?User $organizer = null;

    #[ORM\ManyToOne]
    private ?SonataMediaMedia $image = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'images')]
    private ?self $event = null;

    #[ORM\OneToMany(targetEntity: SonataMediaMedia::class, mappedBy: 'event')]
    private Collection $images;

    #[ORM\ManyToOne]
    private ?SonataMediaMedia $immagine = null;


   


    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->status = self::STATUS_DRAFT;
        $this->registrations = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMaxAttendeesNumber(): ?int
    {
        return $this->maxAttendeesNumber;
    }

    public function setMaxAttendeesNumber(?int $maxAttendeesNumber): static
    {
        $this->maxAttendeesNumber = $maxAttendeesNumber;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRegistrationsStartDate(): ?\DateTimeInterface
    {
        return $this->registrationsStartDate;
    }

    public function setRegistrationsStartDate(?\DateTimeInterface $registrationsStartDate): static
    {
        $this->registrationsStartDate = $registrationsStartDate;

        return $this;
    }

    public function getRegistrationsEndDate2(): ?\DateTimeInterface
    {
        return $this->registrationsEndDate2;
    }

    public function setRegistrationsEndDate2(?\DateTimeInterface $registrationsEndDate2): static
    {
        $this->registrationsEndDate2 = $registrationsEndDate2;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getCoverImageFile(): ?file {
        return $this->coverImageFile;
    }

    public function setCoverImageFile(?File $coverImageFile): void {
        $this->coverImageFile=$coverImageFile;
        if(null !== $coverImageFile) {
            $this->updatedAt=new \DateTimeImmutable();
        }
    }

    public function getCoverImageName(): ?string {
        return $this->coverImageName;
    }

    public function setCoverImageName(?string $coverImageName): void {
        $this->coverImageName=$coverImageName;
    }

    public function getCoverImageSize(): ?int {
        return $this->coverImageSize;
    }

    public function setCoverImageSize(?int $coverImageSize): void {
        $this->coverImageSize=$coverImageSize;
    }

    public function getCoverImageOriginalName(): ?string {
        return $this->coverImageOriginalName;
    }

    public function setCoverImageOriginalName(?string $coverImageOriginalName): void {
        $this->coverImageOriginalName=$coverImageOriginalName;
    }

    /**
     * @return Collection<int, Registration>
     */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): static
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setEvent($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getEvent() === $this) {
                $registration->setEvent(null);
            }
        }

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): static
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getImage(): ?SonataMediaMedia
    {
        return $this->image;
    }

    public function setImage(?SonataMediaMedia $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getEvent(): ?self
    {
        return $this->event;
    }

    public function setEvent(?self $event): static
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return Collection<int, SonataMediaMedia>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(SonataMediaMedia $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setEvent($this);
        }

        return $this;
    }

    public function removeImage(SonataMediaMedia $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getEvent() === $this) {
                $image->setEvent(null);
            }
        }

        return $this;
    }




 

    /**
     * Get the value of immagine
     */
    public function getImmagine(): ?SonataMediaMedia
    {
        return $this->immagine;
    }

    /**
     * Set the value of immagine
     */
    public function setImmagine(?SonataMediaMedia $immagine): self
    {
        $this->immagine = $immagine;

        return $this;
    }
}

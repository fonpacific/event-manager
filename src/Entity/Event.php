<?php

namespace App\Entity;

use App\Model\TimeStampableTrait;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    use TimeStampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxAttendeesNumber = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationStartDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $registrationEndDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $accessStartDate = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $accessEndDate = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getMaxAttendeesNumber(): ?int
    {
        return $this->maxAttendeesNumber;
    }

    public function setMaxAttendeesNumber(?int $maxAttendeesNumber): self
    {
        $this->maxAttendeesNumber = $maxAttendeesNumber;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
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

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
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

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRegistrationStartDate(): ?\DateTimeInterface
    {
        return $this->registrationStartDate;
    }

    public function setRegistrationStartDate(?\DateTimeInterface $registrationStartDate): self
    {
        $this->registrationStartDate = $registrationStartDate;

        return $this;
    }

    public function getRegistrationEndDate(): ?\DateTimeInterface
    {
        return $this->registrationEndDate;
    }

    public function setRegistrationEndDate(?\DateTimeInterface $registrationEndDate): self
    {
        $this->registrationEndDate = $registrationEndDate;

        return $this;
    }

    public function getAccessStartDate(): ?\DateTimeInterface
    {
        return $this->accessStartDate;
    }

    public function setAccessStartDate(?\DateTimeInterface $accessStartDate): self
    {
        $this->accessStartDate = $accessStartDate;

        return $this;
    }

    public function getAccessEndDate(): ?\DateTimeInterface
    {
        return $this->accessEndDate;
    }

    public function setAccessEndDate(?\DateTimeInterface $accessEndDate): self
    {
        $this->accessEndDate = $accessEndDate;

        return $this;
    }

}

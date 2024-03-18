<?php

namespace App\Entity;

use App\Model\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Model\TimeStampableTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: UserRepository::class), ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use TimeStampableTrait, IdentifiableTrait;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Registration::class, mappedBy: 'platformUser')]
    private Collection $registrations;

    #[Assert\Type('string'), Assert\NotBlank(groups: ['edit']), Assert\Length(min:3, max:80)]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $firstName = null;

    #[Assert\Type('string'), Assert\NotBlank(groups: ['edit']), Assert\Length(min:3, max:80)]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[Vich\UploadableField(mapping: 'user_avatar', fileNameProperty: 'avatarImageName', originalName:'avatarImageOriginalName', size: 'avatarImageSize')]
    private ?File $avatarImageFile = null;
    
    #[ORM\Column(nullable: true, type: 'string')]
    private ?string $avatarImageName = null;

    #[ORM\Column(nullable: true, type: 'integer')]
    private ?int $avatarImageSize = null;

    #[ORM\Column(nullable: true, type: 'string')]
    private ?string $avatarImageOriginalName = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull(groups:['edit'])]
    private ?Country $country = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull(groups:['edit'])]
    private ?Province $province = null;

    #[ORM\OneToMany(mappedBy: 'organizer', targetEntity: Event::class)]
    private Collection $events;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'organizer')]
    private Collection $eventi;
    

    public function hasRole(string $role): bool {
        return in_array($role, $this->roles);
    }

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
        $this->eventi = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $registration->setPlatformUser($this);
        }

        return $this;
    }

    public function removeRegistration(Registration $registration): static
    {
        if ($this->registrations->removeElement($registration)) {
            // set the owning side to null (unless already changed)
            if ($registration->getPlatformUser() === $this) {
                $registration->setPlatformUser(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

   

    /**
     * Get the value of avatarImageFile
     */
    public function getAvatarImageFile(): ?File
    {
        return $this->avatarImageFile;
    }

    /**
     * Set the value of avatarImageFile
     */
    public function setAvatarImageFile(?File $avatarImageFile): self
    {
        $this->avatarImageFile = $avatarImageFile;

        return $this;
    }

    /**
     * Get the value of avatarImageName
     */
    public function getAvatarImageName(): ?string
    {
        return $this->avatarImageName;
    }

    /**
     * Set the value of avatarImageName
     */
    public function setAvatarImageName(?string $avatarImageName): self
    {
        $this->avatarImageName = $avatarImageName;

        return $this;
    }

    /**
     * Get the value of avatarImageSize
     */
    public function getAvatarImageSize(): ?int
    {
        return $this->avatarImageSize;
    }

    /**
     * Set the value of avatarImageSize
     */
    public function setAvatarImageSize(?int $avatarImageSize): self
    {
        $this->avatarImageSize = $avatarImageSize;

        return $this;
    }

    /**
     * Get the value of avatarImageOriginalName
     */
    public function getAvatarImageOriginalName(): ?string
    {
        return $this->avatarImageOriginalName;
    }

    /**
     * Set the value of avatarImageOriginalName
     */
    public function setAvatarImageOriginalName(?string $avatarImageOriginalName): self
    {
        $this->avatarImageOriginalName = $avatarImageOriginalName;

        return $this;
    }

    /**
     * Get the value of country
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * Set the value of country
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of province
     */
    public function getProvince(): ?Province
    {
        return $this->province;
    }

    /**
     * Get the value of events
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEventi(): Collection
    {
        return $this->eventi;
    }

    public function addEventi(Event $eventi): static
    {
        if (!$this->eventi->contains($eventi)) {
            $this->eventi->add($eventi);
            $eventi->setOrganizer($this);
        }

        return $this;
    }

    public function removeEventi(Event $eventi): static
    {
        if ($this->eventi->removeElement($eventi)) {
            // set the owning side to null (unless already changed)
            if ($eventi->getOrganizer() === $this) {
                $eventi->setOrganizer(null);
            }
        }

        return $this;
    }
}

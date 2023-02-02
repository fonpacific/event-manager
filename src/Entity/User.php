<?php

namespace App\Entity;


use App\Model\IdentifiableTrait;
use App\Model\TimeStampableTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: UserRepository::class), ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[Vich\Uploadable]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimeStampableTrait, IdentifiableTrait;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'platformUser', targetEntity: Registration::class)]
    private Collection $registrations;

    #[Assert\Type('string'), Assert\NotBlank(groups: ['edit']), Assert\Length(min: 3, max: 80)]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $firstName = null;

    #[Assert\Type('string'), Assert\NotBlank(groups: ['edit']), Assert\Length(min: 3, max: 80)]
    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[Vich\UploadableField(mapping: 'user_avatar', fileNameProperty: 'avatarImageName', originalName: 'avatarImageOriginalName', size: 'avatarImageSize')]
    private ?File $avatarImageFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $avatarImageName = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $avatarImageSize = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $avatarImageOriginalName = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull(groups: ['edit'])]
    private ?Country $country = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Province $province = null;

    public function __construct()
    {
        $this->registrations = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @return Collection<int, Registration> */
    public function getRegistrations(): Collection
    {
        return $this->registrations;
    }

    public function addRegistration(Registration $registration): void
    {
        if (!$this->registrations->contains($registration)) {
            $this->registrations->add($registration);
            $registration->setPlatformUser($this);
        }
    }

    public function removeRegistration(Registration $registration): void
    {
        $this->registrations->removeElement($registration);
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return File|null
     */
    public function getAvatarImageFile(): ?File
    {
        return $this->avatarImageFile;
    }

    /**
     * @param File|null $avatarImageFile
     */
    public function setAvatarImageFile(?File $avatarImageFile): void
    {
        $this->avatarImageFile = $avatarImageFile;
    }

    /**
     * @return string|null
     */
    public function getAvatarImageName(): ?string
    {
        return $this->avatarImageName;
    }

    /**
     * @param string|null $avatarImageName
     */
    public function setAvatarImageName(?string $avatarImageName): void
    {
        $this->avatarImageName = $avatarImageName;
    }

    /**
     * @return int|null
     */
    public function getAvatarImageSize(): ?int
    {
        return $this->avatarImageSize;
    }

    /**
     * @param int|null $avatarImageSize
     */
    public function setAvatarImageSize(?int $avatarImageSize): void
    {
        $this->avatarImageSize = $avatarImageSize;
    }

    /**
     * @return string|null
     */
    public function getAvatarImageOriginalName(): ?string
    {
        return $this->avatarImageOriginalName;
    }

    /**
     * @param string|null $avatarImageOriginalName
     */
    public function setAvatarImageOriginalName(?string $avatarImageOriginalName): void
    {
        $this->avatarImageOriginalName = $avatarImageOriginalName;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     */
    public function setCountry(?Country $country): void
    {
        $this->country = $country;
    }

    /**
     * @return Province|null
     */
    public function getProvince(): ?Province
    {
        return $this->province;
    }

    /**
     * @param Province|null $province
     */
    public function setProvince(?Province $province): void
    {
        $this->province = $province;
    }


}

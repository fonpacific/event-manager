<?php

namespace App\Entity;

use App\Model\IdentifiableTrait;
use App\Model\NameTrait;
use App\Model\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
class Country
{
    use IdentifiableTrait;
    use TimeStampableTrait;
    use NameTrait;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    private ?string $code = null;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Province::class, orphanRemoval: true)]
    private Collection $provinces;

    public function __construct()
    {
        $this->provinces = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return Collection<int, Province>
     */
    public function getProvinces(): Collection
    {
        return $this->provinces;
    }

    public function addProvince(Province $province): self
    {
        if (!$this->provinces->contains($province)) {
            $this->provinces->add($province);
            $province->setCountry($this);
        }

        return $this;
    }

    public function removeProvince(Province $province): self
    {
        if ($this->provinces->removeElement($province)) {
            // set the owning side to null (unless already changed)
            if ($province->getCountry() === $this) {
                $province->setCountry(null);
            }
        }

        return $this;
    }
}
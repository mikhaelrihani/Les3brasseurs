<?php

namespace App\Entity;

use App\Repository\CookingCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CookingCategoryRepository::class)]
class CookingCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, CookingSheet>
     */
    #[ORM\OneToMany(targetEntity: CookingSheet::class, mappedBy: 'cookingCategories')]
    private Collection $cookingSheets;

    public function __construct()
    {
        $this->cookingSheets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, CookingSheet>
     */
    public function getCookingSheets(): Collection
    {
        return $this->cookingSheets;
    }

    public function addCookingSheet(CookingSheet $cookingSheet): static
    {
        if (!$this->cookingSheets->contains($cookingSheet)) {
            $this->cookingSheets->add($cookingSheet);
            $cookingSheet->setCookingCategories($this);
        }

        return $this;
    }

    public function removeCookingSheet(CookingSheet $cookingSheet): static
    {
        if ($this->cookingSheets->removeElement($cookingSheet)) {
            // set the owning side to null (unless already changed)
            if ($cookingSheet->getCookingCategories() === $this) {
                $cookingSheet->setCookingCategories(null);
            }
        }

        return $this;
    }
}

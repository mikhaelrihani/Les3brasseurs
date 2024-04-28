<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DishRepository::class)]
class Dish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\Column(length: 1000)]
    private ?string $comment = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url]
    private ?string $helpUrl = null;

    #[ORM\Column(length: 1000)]
    private ?string $helpText = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, Menu>
     */
    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'dishes')]
    private Collection $menus;

    /**
     * @var Collection<int, picture>
     */
    #[ORM\ManyToMany(targetEntity: picture::class)]
    private Collection $pictures;

    /**
     * @var Collection<int, cookingSheet>
     */
    #[ORM\ManyToMany(targetEntity: cookingSheet::class)]
    private Collection $cookingSheet;

    
    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->cookingSheet = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getHelpUrl(): ?string
    {
        return $this->helpUrl;
    }

    public function setHelpUrl(string $helpUrl): static
    {
        $this->helpUrl = $helpUrl;

        return $this;
    }

    public function getHelpText(): ?string
    {
        return $this->helpText;
    }

    public function setHelpText(string $helpText): static
    {
        $this->helpText = $helpText;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): static
    {
        if (!$this->menus->contains($menu)) {
            $this->menus->add($menu);
            $menu->addDish($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): static
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeDish($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(picture $picture): static
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
        }

        return $this;
    }

    public function removePicture(picture $picture): static
    {
        $this->pictures->removeElement($picture);

        return $this;
    }

    /**
     * @return Collection<int, cookingSheet>
     */
    public function getCookingSheet(): Collection
    {
        return $this->cookingSheet;
    }

    public function addCookingSheet(cookingSheet $cookingSheet): static
    {
        if (!$this->cookingSheet->contains($cookingSheet)) {
            $this->cookingSheet->add($cookingSheet);
        }

        return $this;
    }

    public function removeCookingSheet(cookingSheet $cookingSheet): static
    {
        $this->cookingSheet->removeElement($cookingSheet);

        return $this;
    }
}

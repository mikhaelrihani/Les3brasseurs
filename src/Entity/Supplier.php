<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?string $comments = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, user>
     */
    #[ORM\ManyToMany(targetEntity: User::class,cascade: ["persist"])]
    #[Groups(["supplyWithRelation"])]
    private Collection $staffs;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'suppliers',cascade: ["persist"])]
    #[Groups(["supplyWithRelation"])]
    private Collection $products;

    public function __construct()
    {
        $this->staffs = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): static
    {
        $this->comments = $comments;

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
     * @return Collection<int, user>
     */
    public function getStaffs(): Collection
    {
        return $this->staffs;
    }

    public function addStaff(User $staff): static
    {
        if (!$this->staffs->contains($staff)) {
            $this->staffs->add($staff);
        }

        return $this;
    }

    public function removeStaff(User $staff): static
    {
        $this->staffs->removeElement($staff);

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->addSupplier($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            $product->removeSupplier($this);
        }

        return $this;
    }
}

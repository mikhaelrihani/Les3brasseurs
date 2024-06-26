<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["orderWithRelation"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["orderWithRelation"])]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["orderWithRelation"])]
    private ?string $name = null;

    

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["orderWithRelation"])]
    private ?Date $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["orderWithRelation"])]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["orderWithRelation"])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(["orderWithRelation"])]
    private array $productSnapshot = [];

    #[ORM\Column(length: 255)]
    #[Groups(["orderWithRelation"])]
    private ?string $supplierName = null;

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

    public function getDate(): ?date
    {
        return $this->date;
    }

    public function setDate(?date $date): static
    {
        $this->date = $date;

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

    public function getProductSnapshot(): array
    {
        return $this->productSnapshot;
    }

    public function setProductSnapshot(array $productSnapshot): static
    {
        $this->productSnapshot = $productSnapshot;

        return $this;
    }

    public function getSupplierName(): ?string
    {
        return $this->supplierName;
    }

    public function setSupplierName(string $supplierName): static
    {
        $this->supplierName = $supplierName;

        return $this;
    }
}

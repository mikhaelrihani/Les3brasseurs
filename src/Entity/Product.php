<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product 
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

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Currency]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?string $currency = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?string $conditionning = null;



    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, picture>
     */
    #[ORM\ManyToMany(targetEntity: Picture::class,cascade: ["persist"])]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private Collection $picture;

    /**
     * @var Collection<int, supplier>
     */
    #[ORM\ManyToMany(targetEntity: Supplier::class, inversedBy: 'products',cascade: ["persist"])]
    #[Groups(["productWithRelation"])]
    private Collection $suppliers;

    #[ORM\ManyToOne(cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private ?SupplyType $SupplyType = null;

    /**
     * @var Collection<int, room>
     */
    #[ORM\ManyToMany(targetEntity: Room::class, inversedBy: 'products',cascade: ["persist"])]
    #[Groups(["productWithRelation","supplyWithRelation"])]
    private Collection $rooms;

    

    public function __construct()
    {
        $this->picture = new ArrayCollection();
        $this->suppliers = new ArrayCollection();
        $this->rooms = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getConditionning(): ?string
    {
        return $this->conditionning;
    }

    public function setConditionning(string $conditionning): static
    {
        $this->conditionning = $conditionning;

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
     * @return Collection<int, picture>
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(picture $picture): static
    {
        if (!$this->picture->contains($picture)) {
            $this->picture->add($picture);
        }

        return $this;
    }

    public function removePicture(picture $picture): static
    {
        $this->picture->removeElement($picture);

        return $this;
    }

    /**
     * @return Collection<int, supplier>
     */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
    }

    public function addSupplier(Supplier $supplier): static
    {
        if (!$this->suppliers->contains($supplier)) {
            $this->suppliers->add($supplier);
        }

        return $this;
    }

    public function removeSupplier(Supplier $supplier): static
    {
        $this->suppliers->removeElement($supplier);

        return $this;
    }

    public function getSupplyType(): ?SupplyType
    {
        return $this->SupplyType;
    }

    public function setSupplyType(?SupplyType $SupplyType): static
    {
        $this->SupplyType = $SupplyType;

        return $this;
    }

    /**
     * @return Collection<int, room>
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): static
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms->add($room);
        }

        return $this;
    }

    public function removeRoom(Room $room): static
    {
        $this->rooms->removeElement($room);

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getProductInfo(): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'price'         => $this->price,
            'currency'      => $this->currency,
            'conditionning' => $this->conditionning,
        ];
    }
}

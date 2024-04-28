<?php

namespace App\Entity;

use App\Repository\UserInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserInfosRepository::class)]
#[UniqueEntity('email')]
#[UniqueEntity('phone')]
#[UniqueEntity('whatsApp')]
class UserInfos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["userWithRelation"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["userWithRelation"])]
    private ?string $business = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["userWithRelation"])]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(["userWithRelation"])]
    private ?string $whatsApp = null;

    #[ORM\Column(length: 255)]
    #[Groups(["userWithRelation"])]
    private ?string $avatar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[Groups(["userWithoutRelation","userWithRelation"])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    #[Assert\NotBlank]
    #[Groups(["userWithRelation"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(["userWithRelation"])]
    private ?string $job = null;

    /**
     * @var Collection<int, group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'userInfos', cascade: ['remove'])]
    #[Groups(["userWithRelation"])]
    private Collection $groupList;
    

    public function __construct()
    {
        $this->groupList = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBusiness(): ?string
    {
        return $this->business;
    }

    public function setBusiness(string $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWhatsApp(): ?string
    {
        return $this->whatsApp;
    }

    public function setWhatsApp(string $whatsApp): static
    {
        $this->whatsApp = $whatsApp;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): static
    {
        $this->user = $user;

        return $this;
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

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @return Collection<int, group>
     */
    public function getGroupList(): Collection
    {
        return $this->groupList;
    }

    public function addGroupList(group $groupList): static
    {
        if (!$this->groupList->contains($groupList)) {
            $this->groupList->add($groupList);
        }

        return $this;
    }

    public function removeGroupList(group $groupList): static
    {
        $this->groupList->removeElement($groupList);

        return $this;
    }

}

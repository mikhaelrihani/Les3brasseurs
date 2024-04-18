<?php

namespace App\Entity;

use App\Repository\UserInfosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserInfosRepository::class)]
class UserInfos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $business = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $whatsApp = null;

    #[ORM\Column(length: 255)]
    private ?string $avatar = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\OneToOne(inversedBy: 'userInfos', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    /**
     * @var Collection<int, email>
     */
    #[ORM\OneToMany(targetEntity: email::class, mappedBy: 'sender', orphanRemoval: true)]
    private Collection $emails;

    public function __construct()
    {
        $this->emails = new ArrayCollection();
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

    /**
     * @return Collection<int, email>
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(email $email): static
    {
        if (!$this->emails->contains($email)) {
            $this->emails->add($email);
            $email->setSender($this->getUser());
        }

        return $this;
    }

    public function removeEmail(email $email): static
    {
        if ($this->emails->removeElement($email)) {
            // set the owning side to null (unless already changed)
            if ($email->getSender() === $this) {
                $email->setSender(null);
            }
        }

        return $this;
    }

}

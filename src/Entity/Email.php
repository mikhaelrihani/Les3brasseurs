<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
class Email
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $object = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?bool $delivered = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?date $date_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $sender = null;

    /**
     * @var Collection<int, user>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $receivers;

    /**
     * @var Collection<int, file>
     */
    #[ORM\ManyToMany(targetEntity: File::class)]
    private Collection $files;

    public function __construct()
    {
        $this->receivers = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): static
    {
        $this->object = $object;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isDelivered(): ?bool
    {
        return $this->delivered;
    }

    public function setDelivered(bool $delivered): static
    {
        $this->delivered = $delivered;

        return $this;
    }

    public function getDateId(): ?date
    {
        return $this->date_id;
    }

    public function setDateId(?date $date_id): static
    {
        $this->date_id = $date_id;

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

    public function getSender(): ?user
    {
        return $this->sender;
    }

    public function setSender(?user $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getReceivers(): Collection
    {
        return $this->receivers;
    }

    public function addReceiver(user $receiver): static
    {
        if (!$this->receivers->contains($receiver)) {
            $this->receivers->add($receiver);
        }

        return $this;
    }

    public function removeReceiver(user $receiver): static
    {
        $this->receivers->removeElement($receiver);

        return $this;
    }

    /**
     * @return Collection<int, file>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(file $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
        }

        return $this;
    }

    public function removeFile(file $file): static
    {
        $this->files->removeElement($file);

        return $this;
    }

   

}

<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $docType = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, EmailFile>
     */
    #[ORM\ManyToMany(targetEntity: EmailFile::class, mappedBy: 'file_id')]
    private Collection $emailFiles;

    #[ORM\ManyToOne(targetEntity: Mime::class)]
    #[ORM\JoinColumn(name: "mime_id", referencedColumnName: "id", nullable: false)]
    private ?Mime $mime = null;


    public function __construct()
    {
        $this->emailFiles = new ArrayCollection();
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

    public function getDocType(): ?string
    {
        return $this->docType;
    }

    public function setDocType(string $docType): static
    {
        $this->docType = $docType;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

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
     * @return Collection<int, EmailFile>
     */
    public function getEmailFiles(): Collection
    {
        return $this->emailFiles;
    }

    public function addEmailFile(EmailFile $emailFile): static
    {
        if (!$this->emailFiles->contains($emailFile)) {
            $this->emailFiles->add($emailFile);
            $emailFile->addFileId($this);
        }

        return $this;
    }

    public function removeEmailFile(EmailFile $emailFile): static
    {
        if ($this->emailFiles->removeElement($emailFile)) {
            $emailFile->removeFileId($this);
        }

        return $this;
    }

    public function getMimeId(): ?mime
    {
        return $this->mime;
    }

    public function setMimeId(?mime $mime): static
    {
        $this->mime = $mime;

        return $this;
    }
}

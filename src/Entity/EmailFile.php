<?php

namespace App\Entity;

use App\Repository\EmailFileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailFileRepository::class)]
class EmailFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, file>
     */
    #[ORM\ManyToMany(targetEntity: file::class, inversedBy: 'emailFiles')]
    #[ORM\JoinColumn(targetEntity: file::class, name: 'file_id', referencedColumnName: 'id')]
    private Collection $files;

    /**
     * @var Collection<int, email>
     */
    #[ORM\ManyToMany(targetEntity: email::class)]
    private Collection $emails;

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->emails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, file>
     */
    public function getFileId(): Collection
    {
        return $this->files;
    }

    public function addFileId(file $fileId): static
    {
        if (!$this->files->contains($fileId)) {
            $this->files->add($fileId);
        }

        return $this;
    }

    public function removeFileId(file $fileId): static
    {
        $this->files->removeElement($fileId);

        return $this;
    }

    /**
     * @return Collection<int, email>
     */
    public function getEmailId(): Collection
    {
        return $this->emails;
    }

    public function addEmailId(email $emailId): static
    {
        if (!$this->emails->contains($emailId)) {
            $this->emails->add($emailId);
        }

        return $this;
    }

    public function removeEmailId(email $emailId): static
    {
        $this->emails->removeElement($emailId);

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UsersUserInfosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersUserInfosRepository::class)]
class UsersUserInfos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?userInfos $userInfos_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUserInfosId(): ?userInfos
    {
        return $this->userInfos_id;
    }

    public function setUserInfosId(userInfos $userInfos_id): static
    {
        $this->userInfos_id = $userInfos_id;

        return $this;
    }
}


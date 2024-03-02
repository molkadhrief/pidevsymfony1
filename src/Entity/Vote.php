<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $up = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $down = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[Assert\NotBlank]
    private ?Post $post = null;

    #[ORM\Column(length: 255)]
    private ?string $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function isUp(): ?bool
    {
        return $this->up;
    }

    public function setUp(bool $up): static
    {
        $this->up = $up;

        return $this;
    }

    public function isDown(): ?bool
    {
        return $this->down;
    }

    public function setDown(bool $down): static
    {
        $this->down = $down;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }

}

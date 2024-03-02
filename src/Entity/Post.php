<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank]
    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comment')]
    private ?self $post = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: self::class)]
    private Collection $comment;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Postimage::class, orphanRemoval: true)]
    private Collection $postimages;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $approved = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Vote::class,cascade: ["remove"])]
    private Collection $votes;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?bool $bigPost = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $upVoteNum = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $downVoteNum = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $createdAt = null;


    public function __construct()
    {
        $this->comment = new ArrayCollection();
        $this->postimages = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getPost(): ?self
    {
        return $this->post;
    }

    public function setPost(?self $post): static
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(self $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(self $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Postimage>
     */
    public function getPostimages(): Collection
    {
        return $this->postimages;
    }

    public function addPostimage(Postimage $postimage): static
    {
        if (!$this->postimages->contains($postimage)) {
            $this->postimages->add($postimage);
            $postimage->setPost($this);
        }

        return $this;
    }

    public function removePostimage(Postimage $postimage): static
    {
        if ($this->postimages->removeElement($postimage)) {
            // set the owning side to null (unless already changed)
            if ($postimage->getPost() === $this) {
                $postimage->setPost(null);
            }
        }

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): static
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setPost($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getPost() === $this) {
                $vote->setPost(null);
            }
        }

        return $this;
    }

    public function isBigPost(): ?bool
    {
        return $this->bigPost;
    }

    public function setBigPost(bool $bigPost): static
    {
        $this->bigPost = $bigPost;

        return $this;
    }

    public function getUpVoteNum(): ?int
    {
        return $this->upVoteNum;
    }

    public function setUpVoteNum(int $upVoteNum): static
    {
        $this->upVoteNum = $upVoteNum;

        return $this;
    }

    public function getDownVoteNum(): ?int
    {
        return $this->downVoteNum;
    }

    public function setDownVoteNum(int $downVoteNum): static
    {
        $this->downVoteNum = $downVoteNum;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}

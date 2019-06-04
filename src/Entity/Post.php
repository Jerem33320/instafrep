<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post extends Model
{

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=280)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public = false;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(mimeTypes={ "image/gif", "image/jpeg", "image/png" })
     */
    private $attachment;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\GreaterThanOrEqual("today")
     */
    private $publishedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true, cascade={"persist"})
     */
    private $comments;

    /**
     * The number of comments for this post
     * @var int
     */
    private $nbComments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="liked")
     */
    private $likers;


    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->likers = new ArrayCollection();
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }


    public function getAttachment()
    {
        return $this->attachment;
    }

    public function setAttachment($attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        $comments = $this->comments;
        $this->nbComments = $comments->count();

        return $comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Get the number of comments
     *
     * (If not fetched, the comments are extracted from the database)
     *
     * @return integer
     */
    public function getNbComments():int
    {
        if (isset($this->nbComments)) {
            return $this->nbComments;
        }

        return $this->getComments()->count();
    }

    /**
     * @param integer $nbComments
     * @return Post
     */
    public function setNbComments(int $nbComments)
    {
        $this->nbComments = $nbComments;
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getLikers(): Collection
    {
        return $this->likers;
    }

    public function addLiker(User $liker): self
    {
        if (!$this->likers->contains($liker)) {
            $this->likers[] = $liker;
            $liker->like($this);
        }

        return $this;
    }

    public function removeLiker(User $liker): self
    {
        if ($this->likers->contains($liker)) {
            $this->likers->removeElement($liker);
            $liker->unlike($this);
        }

        return $this;
    }
}

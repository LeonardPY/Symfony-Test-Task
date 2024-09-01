<?php

namespace App\Entity;

use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $content;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $image;

    #[ORM\Column(type: 'datetime_immutable', length: 255, nullable: true)]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', length: 255, nullable: true)]
    private DateTimeImmutable $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}

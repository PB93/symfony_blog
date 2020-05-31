<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OnetoMany(targetEntity="Article", mappedBy="category", cascade="remove")
    */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CategoryComment", mappedBy="category", cascade="remove")
     * @OrderBy({"id" = "DESC"})
     */
    private $comments;

    public function __construct()
{
    $this->articles = new ArrayCollection();
    $this->comments = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles() {
        return $this->articles;
    }

    /**
     * Add comment
     *
     * @param \App\Entity\CategoryComment $comment
     *
     * @return Category
     */
    public function addComment(CategoryComment $comment) {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments(){
        return $this->comments;
    }
}

<?php

namespace App\Entity;

use App\Repository\StylesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StylesRepository::class)
 */
class Styles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $styleNom;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="style", orphanRemoval=true)
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStyleNom(): ?string
    {
        return $this->styleNom;
    }

    public function setStyleNom(string $styleNom): self
    {
        $this->styleNom = $styleNom;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setStyle($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getStyle() === $this) {
                $article->setStyle(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->styleNom;
    }
}

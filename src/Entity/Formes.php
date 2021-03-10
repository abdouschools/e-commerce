<?php

namespace App\Entity;

use App\Repository\FormesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormesRepository::class)
 */
class Formes
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
    private $formNom;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="formes")
     */
    private $article;

    public function __construct()
    {
        $this->article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormNom(): ?string
    {
        return $this->formNom;
    }

    public function setFormNom(string $formNom): self
    {
        $this->formNom = $formNom;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->article->contains($article)) {
            $this->article[] = $article;
            $article->setFormes($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getFormes() === $this) {
                $article->setFormes(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->formNom;
    }
}

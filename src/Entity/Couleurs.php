<?php

namespace App\Entity;

use App\Repository\CouleursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CouleursRepository::class)
 */
class Couleurs
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
    private $nomCouleur;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="couleurs")
     */
    private $Article;

    public function __construct()
    {
        $this->Article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCouleur(): ?string
    {
        return $this->nomCouleur;
    }

    public function setNomCouleur(string $nomCouleur): self
    {
        $this->nomCouleur = $nomCouleur;

        return $this;
    }


    /**
     * @return Collection|Articles[]
     */
    public function getArticle(): Collection
    {
        return $this->Article;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->Article->contains($article)) {
            $this->Article[] = $article;
            $article->setCouleurs($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->Article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCouleurs() === $this) {
                $article->setCouleurs(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nomCouleur;
    }
}

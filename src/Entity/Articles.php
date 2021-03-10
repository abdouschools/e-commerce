<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\ArticlesRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 * @Vich\Uploadable
 * 
 */
class Articles
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @Gedmo\Slug(fields={"nom"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255 ,nullable=true )
     */
    private $thumbnail;

    /**
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="thumbnail")
     * @var File
     */
    private $thumbnailFile;


    /**
     * @ORM\Column(type="integer")
     */
    private $prix;


    /**
     * @var \DateTime $datecreation
     * 
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     *  @var \DateTime $updatedAt
     * 
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime" ,nullable=true )
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="float")
     */
    private $poids;

    /**
     * @ORM\ManyToOne(targetEntity=Styles::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $style;

    /**
     * @ORM\ManyToOne(targetEntity=Marques::class, inversedBy="article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marques;

    /**
     * @ORM\ManyToOne(targetEntity=Couleurs::class, inversedBy="Article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $couleurs;

    /**
     * @ORM\ManyToOne(targetEntity=Formes::class, inversedBy="article")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formes;

    /**
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="post", cascade={"persist","remove","refresh"})
     */
    private $attachments;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="acticles", orphanRemoval=true ,cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tvaMultiplication;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $tvaNom;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $valeur;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $meta;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $imageAlt;



    public function __construct()
    {
        $this->updatedAt = new \DateTime();
        $this->attachments = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }



    /**
     * Get the value of thumbnailFile
     *
     * @return  File
     */
    public function getThumbnailFile()
    {
        return $this->thumbnailFile;
    }

    /**
     * Set the value of thumbnailFile
     *
     * @param  File  $thumbnailFile
     *
     * @return  self
     */
    public function setThumbnailFile(File $thumbnailFile)
    {
        $this->thumbnailFile = $thumbnailFile;
        if (null !== $thumbnailFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getCategorie(): ?Categories
    {
        return $this->categorie;
    }

    public function setCategorie(?Categories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getStyle(): ?Styles
    {
        return $this->style;
    }

    public function setStyle(?Styles $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getMarques(): ?Marques
    {
        return $this->marques;
    }

    public function setMarques(?Marques $marques): self
    {
        $this->marques = $marques;

        return $this;
    }

    public function getCouleurs(): ?Couleurs
    {
        return $this->couleurs;
    }

    public function setCouleurs(?Couleurs $couleurs): self
    {
        $this->couleurs = $couleurs;

        return $this;
    }

    public function getFormes(): ?Formes
    {
        return $this->formes;
    }

    public function setFormes(?Formes $formes): self
    {
        $this->formes = $formes;

        return $this;
    }


    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setPost($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getPost() === $this) {
                $attachment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of thumbnail
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set the value of thumbnail
     *
     * @return  self
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setActicles($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getActicles() === $this) {
                $comment->setActicles(null);
            }
        }

        return $this;
    }

    public function getTvaMultiplication(): ?float
    {
        return $this->tvaMultiplication;
    }

    public function setTvaMultiplication(?float $tvaMultiplication): self
    {
        $this->tvaMultiplication = $tvaMultiplication;

        return $this;
    }

    public function getTvaNom(): ?string
    {
        return $this->tvaNom;
    }

    public function setTvaNom(?string $tvaNom): self
    {
        $this->tvaNom = $tvaNom;

        return $this;
    }

    public function getValeur(): ?float
    {
        return $this->valeur;
    }

    public function setValeur(?float $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(?string $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function getImageAlt(): ?string
    {
        return $this->imageAlt;
    }

    public function setImageAlt(?string $imageAlt): self
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }
}

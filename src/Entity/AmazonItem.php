<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AmazonItemRepository")
 */
class AmazonItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $productId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 80,
     *      minMessage = "TitleForEbay must be at least {{ limit }} characters long",
     *      maxMessage = "TitleForEbay cannot be longer than {{ limit }} characters"
     * )
     */
    private $titleForEbay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     */
    private $stock;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotBlank
     */
    private $stockForEbay=0;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;
    
    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotBlank
     */
    private $priceForEbay=0;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    private $descriptionForEbay;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $crawled;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AmazonCategory")
     * @Assert\NotBlank
     */
    private $categoryForEbay;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageUrl;
    
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AmazonImage", mappedBy="amazonProductId",cascade={"persist"})
     */
    private $images;
    
     /**
     * @ORM\Column(type="boolean")
     */
    private $active;
    
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId($productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): self
    {
        $this->title = $title;

        return $this;
    }
    
    
    public function getTitleForEbay()
    {
        return $this->titleForEbay;
    }

    public function setTitleForEbay($titleForEbay): self
    {
        $this->titleForEbay = $titleForEbay;

        return $this;
    }
    
    

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock): self
    {
        $this->stock = $stock;

        return $this;
    }
    
    
    public function getStockForEbay()
    {
        return $this->stockForEbay;
    }

    public function setStockForEbay($stockForEbay): self
    {
        $this->stockForEbay = $stockForEbay;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }
    
    public function getPriceForEbay()
    {
        return $this->priceForEbay;
    }

    public function setPriceForEbay($priceForEbay): self
    {
        $this->priceForEbay = $priceForEbay;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    
    
    public function getDescriptionForEbay()
    {
        return $this->descriptionForEbay;
    }

    public function setDescriptionForEbay($descriptionForEbay): self
    {
        $this->descriptionForEbay = $descriptionForEbay;

        return $this;
    }
    
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCrawled(): ?\DateTimeInterface
    {
        return $this->crawled;
    }

    public function setCrawled(\DateTimeInterface $crawled): self
    {
        $this->crawled = $crawled;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }
    
    public function getCategoryForEbay()
    {
        return $this->categoryForEbay;
    }

    public function setCategoryForEbay($categoryForEbay): self
    {
        $this->categoryForEbay = $categoryForEbay;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
    
    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(AmazonImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAmazonProductId($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getAmazonProductId() === $this) {
                $image->setAmazonProductId(null);
            }
        }

        return $this;
    }
    
    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}

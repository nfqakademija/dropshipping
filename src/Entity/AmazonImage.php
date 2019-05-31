<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AmazonImageRepository")
 */
class AmazonImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AmazonItem", inversedBy="images")
     */
    private $amazonProductId;

    /**
     * @ORM\Column(type="text")
     */
    private $imageLink;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmazonProductId(): ?AmazonItem
    {
        return $this->amazonProductId;
    }

    public function setAmazonProductId(?AmazonItem $amazonProductId): self
    {
        $this->amazonProductId = $amazonProductId;

        return $this;
    }

    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    public function setImageLink(string $imageLink): self
    {
        $this->imageLink = $imageLink;

        return $this;
    }
}

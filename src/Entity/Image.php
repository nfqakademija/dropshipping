<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AliExpressItem", inversedBy="images")
     */
    private $aliExpressProductId;

    /**
     * @ORM\Column(type="text")
     */
    private $imageLink;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAliExpressProductId(): ?AliExpressItem
    {
        return $this->aliExpressProductId;
    }

    public function setAliExpressProductId(?AliExpressItem $aliExpressProductId): self
    {
        $this->aliExpressProductId = $aliExpressProductId;

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

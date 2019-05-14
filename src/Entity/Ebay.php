<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EbayRepository")
 */
class Ebay
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
    private $OrderID;

    /**
     * @ORM\Column(type="integer")
     */
    private $UserID;

    /**
     * @ORM\Column(type="integer")
     */
    private $shipped_status;

    /**
     * @ORM\Column(type="integer")
     */
    private $paid_status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderID(): ?string
    {
        return $this->OrderID;
    }

    public function setOrderID(string $OrderID): self
    {
        $this->OrderID = $OrderID;

        return $this;
    }

    public function getUserID(): ?int
    {
        return $this->UserID;
    }

    public function setUserID(int $UserID): self
    {
        $this->UserID = $UserID;

        return $this;
    }

    public function getShippedStatus(): ?int
    {
        return $this->shipped_status;
    }

    public function setShippedStatus(int $shipped_status): self
    {
        $this->shipped_status = $shipped_status;

        return $this;
    }

    public function getPaidStatus(): ?int
    {
        return $this->paid_status;
    }

    public function setPaidStatus(int $paid_status): self
    {
        $this->paid_status = $paid_status;

        return $this;
    }
}

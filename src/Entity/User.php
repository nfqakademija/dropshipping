<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=55)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $planId;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $planStartTime;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $planExpireTime;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ebayCountry;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $oAuthToken;

    /**
     * @ORM\Column(type="string", length=2555, nullable=true)
     */
    private $oAuthTokenRefresh;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tokenExpired;

    /**
     * @ORM\Column(type="string", length=2555, nullable=true)
     */
    private $oldEbayAuth;

    /**
     * @ORM\Column(type="string", length=2555, nullable=true)
     */
    private $oldExpiredTime;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AliExpressItem", mappedBy="user")
     */
    private $aliExpressItems;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EbayItem", mappedBy="user")
     */
    private $ebayItems;

    public function __construct()
    {
        $this->aliExpressItems = new ArrayCollection();
        $this->ebayItems = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPlanId(): ?int
    {
        return $this->planId;
    }
    public function setPlanId(?int $planId): self
    {
        $this->planId = $planId;
        return $this;
    }
    public function getPlanStartTime(): ?\DateTimeInterface
    {
        return $this->planStartTime;
    }
    public function setPlanStartTime(?\DateTimeInterface $planStartTime): self
    {
        $this->planStartTime = $planStartTime;
        return $this;
    }
    public function getPlanExpireTime(): ?\DateTimeInterface
    {
        return $this->planExpireTime;
    }
    public function setPlanExpireTime(?\DateTimeInterface $planExpireTime): self
    {
        $this->planExpireTime = $planExpireTime;
        return $this;
    }
    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
    public function getEbayCountry(): ?int
    {
        return $this->ebayCountry;
    }
    public function setEbayCountry(?int $ebayCountry): self
    {
        $this->ebayCountry = $ebayCountry;
        return $this;
    }

    public function __toString() {
        return $this->firstName." ".$this->lastName;
    }

    public function getOauthToken(): ?string
    {
        return $this->oAuthToken;
    }
    public function setOauthToken(string $token): self
    {
        $this->oAuthToken = $token;
        return $this;
    }
    public function getOauthRefreshToken(): ?string
    {
        return $this->oAuthTokenRefresh;
    }
    public function setOauthRefreshToken(string $refreshToken): self
    {
        $this->oAuthTokenRefresh = $refreshToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenExpired()
    {
        return $this->tokenExpired;
    }

    /**
     * @param mixed $tokenExpired
     */
    public function setTokenExpired($tokenExpired): void
    {
        $this->tokenExpired = $tokenExpired;
    }

    /**
     * @return mixed
     */
    public function getOldEbayAuth()
    {
        return $this->oldEbayAuth;
    }

    /**
     * @param mixed $oldEbayAuth
     */
    public function setOldEbayAuth($oldEbayAuth): void
    {
        $this->oldEbayAuth = $oldEbayAuth;
    }

    /**
     * @return mixed
     */
    public function getOldExpiredTime()
    {
        return $this->oldExpiredTime;
    }

    /**
     * @param mixed $oldExpiredTime
     */
    public function setOldExpiredTime($oldExpiredTime): void
    {
        $this->oldExpiredTime = $oldExpiredTime;
    }

    /**
     * @return Collection|AliExpressItem[]
     */
    public function getAliExpressItems(): Collection
    {
        return $this->aliExpressItems;
    }

    public function addAliExpressItem(AliExpressItem $aliExpressItem): self
    {
        if (!$this->aliExpressItems->contains($aliExpressItem)) {
            $this->aliExpressItems[] = $aliExpressItem;
            $aliExpressItem->setUser($this);
        }

        return $this;
    }

    public function removeAliExpressItem(AliExpressItem $aliExpressItem): self
    {
        if ($this->aliExpressItems->contains($aliExpressItem)) {
            $this->aliExpressItems->removeElement($aliExpressItem);
            // set the owning side to null (unless already changed)
            if ($aliExpressItem->getUser() === $this) {
                $aliExpressItem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EbayItem[]
     */
    public function getEbayItems(): Collection
    {
        return $this->ebayItems;
    }

    public function addEbayItem(EbayItem $ebayItem): self
    {
        if (!$this->ebayItems->contains($ebayItem)) {
            $this->ebayItems[] = $ebayItem;
            $ebayItem->setUser($this);
        }

        return $this;
    }

    public function removeEbayItem(EbayItem $ebayItem): self
    {
        if ($this->ebayItems->contains($ebayItem)) {
            $this->ebayItems->removeElement($ebayItem);
            // set the owning side to null (unless already changed)
            if ($ebayItem->getUser() === $this) {
                $ebayItem->setUser(null);
            }
        }

        return $this;
    }
}

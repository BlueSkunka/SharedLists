<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PurchaseRepository::class)
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mainPurchase;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     */
    private $share;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity=ListingItem::class, inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listingItem;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainPurchase(): ?bool
    {
        return $this->mainPurchase;
    }

    public function setMainPurchase(bool $mainPurchase): self
    {
        $this->mainPurchase = $mainPurchase;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getShare(): ?int
    {
        return $this->share;
    }

    public function setShare(int $share): self
    {
        $this->share = $share;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getListingItem(): ?ListingItem
    {
        return $this->listingItem;
    }

    public function setListingItem(?ListingItem $listingItem): self
    {
        $this->listingItem = $listingItem;

        return $this;
    }
}

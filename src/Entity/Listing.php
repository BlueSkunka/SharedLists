<?php

namespace App\Entity;

use App\Repository\ListingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\SafetyEntityTraits;
use App\Entity\Functions\ListingFunctions;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ListingRepository::class)
 */
class Listing
{
    use SafetyEntityTraits;
    use ListingFunctions;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ListGroup::class, inversedBy="listings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listingGroup;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="listings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ListingItem::class, mappedBy="listing", cascade={"persist", "remove"})
     */
    private $listingItems;

    public function __construct()
    {
        $this->listingItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListingGroup(): ?ListGroup
    {
        return $this->listingGroup;
    }

    public function setListingGroup(?ListGroup $listingGroup): self
    {
        $this->listingGroup = $listingGroup;

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

    /**
     * @return Collection|ListingItem[]
     */
    public function getListingItems(): Collection
    {
        return $this->listingItems;
    }

    public function addListingItem(ListingItem $listingItem): self
    {
        if (!$this->listingItems->contains($listingItem)) {
            $this->listingItems[] = $listingItem;
            $listingItem->setListing($this);
        }

        return $this;
    }

    public function removeListingItem(ListingItem $listingItem): self
    {
        if ($this->listingItems->contains($listingItem)) {
            $this->listingItems->removeElement($listingItem);
            // set the owning side to null (unless already changed)
            if ($listingItem->getListing() === $this) {
                $listingItem->setListing(null);
            }
        }

        return $this;
    }
}

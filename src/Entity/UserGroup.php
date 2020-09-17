<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\SafetyEntityTraits;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 */
class UserGroup
{
    use SafetyEntityTraits;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdUserGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="memberUserGroups")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity=ListGroup::class, mappedBy="userGroup")
     */
    private $listGroups;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->listGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }

    /**
     * @return Collection|ListGroup[]
     */
    public function getListGroups(): Collection
    {
        return $this->listGroups;
    }

    public function addListGroup(ListGroup $listGroup): self
    {
        if (!$this->listGroups->contains($listGroup)) {
            $this->listGroups[] = $listGroup;
            $listGroup->setUserGroup($this);
        }

        return $this;
    }

    public function removeListGroup(ListGroup $listGroup): self
    {
        if ($this->listGroups->contains($listGroup)) {
            $this->listGroups->removeElement($listGroup);
            // set the owning side to null (unless already changed)
            if ($listGroup->getUserGroup() === $this) {
                $listGroup->setUserGroup(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\SafetyEntityTraits;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username","email"}, message="There is already an account with this username or email")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $displayName;

    /**
     * @ORM\OneToMany(targetEntity=FriendRequest::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sentFriendRequests;

    /**
     * @ORM\OneToMany(targetEntity=FriendRequest::class, mappedBy="receiver", orphanRemoval=true)
     */
    private $receivedFriendRequests;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $friends;

    /**
     * @ORM\OneToMany(targetEntity=UserGroupRequest::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sentUserGroupRequests;

    /**
     * @ORM\OneToMany(targetEntity=UserGroupRequest::class, mappedBy="receiver")
     */
    private $receivedUserGroupRequests;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="creator")
     */
    private $createdUserGroups;

    /**
     * @ORM\ManyToMany(targetEntity=UserGroup::class, mappedBy="members")
     */
    private $memberUserGroups;

    /**
     * @ORM\OneToMany(targetEntity=Listing::class, mappedBy="user")
     */
    private $listings;

    /**
     * @ORM\Column(type="uuid")
     */
    private $publicId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->sentUserGroupRequests = new ArrayCollection();
        $this->receivedUserGroupRequests = new ArrayCollection();
        $this->createdUserGroups = new ArrayCollection();
        $this->memberUserGroups = new ArrayCollection();
        $this->listings = new ArrayCollection();
        $this->publicId = Uuid::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return Collection|FriendRequest[]
     */
    public function getSentFriendRequests(): Collection
    {
        return $this->sentFriendRequests;
    }

    public function addSentFriendRequest(FriendRequest $sentFriendRequest): self
    {
        if (!$this->sentFriendRequests->contains($sentFriendRequest)) {
            $this->sentFriendRequests[] = $sentFriendRequest;
            $sentFriendRequest->setSender($this);
        }

        return $this;
    }

    public function removeSentFriendRequest(FriendRequest $sentFriendRequest): self
    {
        if ($this->sentFriendRequests->contains($sentFriendRequest)) {
            $this->sentFriendRequests->removeElement($sentFriendRequest);
            // set the owning side to null (unless already changed)
            if ($sentFriendRequest->getSender() === $this) {
                $sentFriendRequest->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FriendRequest[]
     */
    public function getReceivedFriendRequests(): Collection
    {
        return $this->receivedFriendRequests;
    }

    public function addReceivedFriendRequest(FriendRequest $receivedFriendRequest): self
    {
        if (!$this->receivedFriendRequests->contains($receivedFriendRequest)) {
            $this->receivedFriendRequests[] = $receivedFriendRequest;
            $receivedFriendRequest->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedFriendRequest(FriendRequest $receivedFriendRequest): self
    {
        if ($this->receivedFriendRequests->contains($receivedFriendRequest)) {
            $this->receivedFriendRequests->removeElement($receivedFriendRequest);
            // set the owning side to null (unless already changed)
            if ($receivedFriendRequest->getReceiver() === $this) {
                $receivedFriendRequest->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
        }

        return $this;
    }

    /**
     * @return Collection|UserGroupRequest[]
     */
    public function getSentUserGroupRequests(): Collection
    {
        return $this->sentUserGroupRequests;
    }

    public function addSentUserGroupRequest(UserGroupRequest $sentUserGroupRequest): self
    {
        if (!$this->sentUserGroupRequests->contains($sentUserGroupRequest)) {
            $this->sentUserGroupRequests[] = $sentUserGroupRequest;
            $sentUserGroupRequest->setSender($this);
        }

        return $this;
    }

    public function removeSentUserGroupRequest(UserGroupRequest $sentUserGroupRequest): self
    {
        if ($this->sentUserGroupRequests->contains($sentUserGroupRequest)) {
            $this->sentUserGroupRequests->removeElement($sentUserGroupRequest);
            // set the owning side to null (unless already changed)
            if ($sentUserGroupRequest->getSender() === $this) {
                $sentUserGroupRequest->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserGroupRequest[]
     */
    public function getReceivedUserGroupRequests(): Collection
    {
        return $this->receivedUserGroupRequests;
    }

    public function addReceivedUserGroupRequest(UserGroupRequest $receivedUserGroupRequest): self
    {
        if (!$this->receivedUserGroupRequests->contains($receivedUserGroupRequest)) {
            $this->receivedUserGroupRequests[] = $receivedUserGroupRequest;
            $receivedUserGroupRequest->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedUserGroupRequest(UserGroupRequest $receivedUserGroupRequest): self
    {
        if ($this->receivedUserGroupRequests->contains($receivedUserGroupRequest)) {
            $this->receivedUserGroupRequests->removeElement($receivedUserGroupRequest);
            // set the owning side to null (unless already changed)
            if ($receivedUserGroupRequest->getReceiver() === $this) {
                $receivedUserGroupRequest->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getCreatedUserGroups(): Collection
    {
        return $this->createdUserGroups;
    }

    public function addCreatedUserGroup(UserGroup $createdUserGroup): self
    {
        if (!$this->createdUserGroups->contains($createdUserGroup)) {
            $this->createdUserGroups[] = $createdUserGroup;
            $createdUserGroup->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedUserGroup(UserGroup $createdUserGroup): self
    {
        if ($this->createdUserGroups->contains($createdUserGroup)) {
            $this->createdUserGroups->removeElement($createdUserGroup);
            // set the owning side to null (unless already changed)
            if ($createdUserGroup->getCreator() === $this) {
                $createdUserGroup->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getMemberUserGroups(): Collection
    {
        return $this->memberUserGroups;
    }

    public function addMemberUserGroup(UserGroup $memberUserGroup): self
    {
        if (!$this->memberUserGroups->contains($memberUserGroup)) {
            $this->memberUserGroups[] = $memberUserGroup;
            $memberUserGroup->addMember($this);
        }

        return $this;
    }

    public function removeMemberUserGroup(UserGroup $memberUserGroup): self
    {
        if ($this->memberUserGroups->contains($memberUserGroup)) {
            $this->memberUserGroups->removeElement($memberUserGroup);
            $memberUserGroup->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|Listing[]
     */
    public function getListings(): Collection
    {
        return $this->listings;
    }

    public function addListing(Listing $listing): self
    {
        if (!$this->listings->contains($listing)) {
            $this->listings[] = $listing;
            $listing->setUser($this);
        }

        return $this;
    }

    public function removeListing(Listing $listing): self
    {
        if ($this->listings->contains($listing)) {
            $this->listings->removeElement($listing);
            // set the owning side to null (unless already changed)
            if ($listing->getUser() === $this) {
                $listing->setUser(null);
            }
        }

        return $this;
    }

    public function getPublicId()
    {
        return $this->publicId;
    }

    public function setPublicId($publicId): self
    {
        $this->publicId = $publicId;

        return $this;
    }

    /**
     * USER INTERFACE METHODS
     */
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    
}

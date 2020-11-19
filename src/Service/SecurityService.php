<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Entity\UserGroup;

class SecurityService {

    private $em;

    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->em = $entityManagerInterface;
    }

    /**
     * UserGroup
     */
    public function isUserGroupMember(User $user, UserGroup $userGroup) {
        if ($userGroup->getMembers()->contains($user) || $userGroup->getCreator()->getId() == $user->getId())
            return true;
        else    
            return false;
    }
}
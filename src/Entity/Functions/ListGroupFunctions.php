<?php

namespace App\Entity\Functions;

use App\Entity\User;

trait ListGroupFunctions {

    public function hasUserCreatedListing(User $user)
    {
        foreach ($this->listings as $listing) {
            if ($listing->getUser()->getId() == $user->getId())
                return true;
        }

        return false;
    }

}
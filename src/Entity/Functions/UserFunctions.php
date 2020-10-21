<?php

namespace App\Entity\Functions;

use App\Entity\ListGroup;

trait UserFunctions
{
    public function getListingByListGroup(ListGroup $listGroup)
    {
        foreach ($this->getListings() as $list) {
            if ($list->getListingGroup()->getId() == $listGroup->getId())
                return $list;
        }

        return null;
    }    
}

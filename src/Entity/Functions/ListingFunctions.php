<?php

namespace App\Entity\Functions;

trait ListingFunctions {
    public function setListingItemsListing() {
        foreach ($this->getListingItems() as $item) {
            $item->setListing($this);
        }
        
        return $this;
    }
}
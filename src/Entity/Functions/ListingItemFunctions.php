<?php

namespace App\Entity\Functions;

trait ListingItemFunctions {
    public function isFirstPurchase() {
        if (\count($this->getPurchases()) < 1)
            return true;
        else 
            return false;
    }

    public function getMaxShare() {
        $max = 100;

        foreach ($this->getPurchases() as $purchase) {
            $max -= $purchase->getShare();
        }
         
        return $max;
    }
}
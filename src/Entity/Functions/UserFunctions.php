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

    public function getPendingSentFriendRequests() {
        $requests = [];

        foreach ($this->getSentFriendRequests() as $request ) {
            if (\is_null($request->getState()))
                $requests[] = $request;
        }

        return $requests;
    }

    public function getPendingReceivedFriendRequests() {
        $requests = [];

        foreach ($this->getReceivedFriendRequests() as $request ) {
            if (\is_null($request->getState()))
                $requests[] = $request;
        }

        return $requests;
    }

    public function getPurchaseByStateAndMain(bool $state, bool $main) {
        $purchases = [];

        foreach ($this->getPurchases() as $purchase) {
            if ($purchase->getState() == $state & $purchase->getMainPurchase() == $main)
                $purchases[] = $purchase;
        }

        return $purchases;
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    public function purchases()
    {
        return $this->render('purchase/index.html.twig', [

        ]);
    }
}

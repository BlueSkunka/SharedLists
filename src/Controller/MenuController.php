<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{

    public function mainMenu()
    {
        return $this->render('menu/main_menu.html.twig', [
        ]);
    }
}

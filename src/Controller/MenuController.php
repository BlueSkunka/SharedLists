<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MenuController extends AbstractController
{

    public function mainMenu(Request $request, $route)
    {
        return $this->render('menu/main_menu.html.twig', [
            'route' => $route
        ]);
    }
}

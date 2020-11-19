<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SecurityService;

class HomeController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService) {
        $this->securityService = $securityService;
    }
    
    public function home()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('home/index.html.twig', [
        ]);
    }
}

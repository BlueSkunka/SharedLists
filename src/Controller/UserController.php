<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\SecurityService;

class UserController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService) {
        $this->securityService = $securityService;
    }
    
    public function infos(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('user/infos.html.twig', [
        ]);
    }
}
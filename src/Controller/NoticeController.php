<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Notice;

class NoticeController extends AbstractController
{
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $notices = $em->getRepository(Notice::class)->findBy(['seen' => false]);

        return $this->render('notice/index.html.twig', [
            'notices' => $notices,
        ]);
    }

    public function noticeSeen(Notice $notice) {
        $notice->setSeen(true);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($notice);
        $em->flush();

        return new JsonResponse([
            'statut' => 'ok'
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\SecurityService;
use App\Entity\Purchase;

class PurchaseController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService) {
        $this->securityService = $securityService;
    }
    
    public function purchases()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('purchase/index.html.twig', [

        ]);
    }

    public function done(Purchase $purchase) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        
        $purchase->setState(true);

        $em->persist($purchase);
        $em->flush();

        $html = $this->render('purchase/purchase_done_item.html.twig', ['purchase' => $purchase]);
        
        return new JsonResponse([
            'statut' => 'ok',
            'isMain' => $purchase->getMainPurchase(),
            'html' => $html->getContent()
        ]);

    }

    public function cancel(Purchase $purchase) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $purchase->setState(false);

        $em->persist($purchase);
        $em->flush();

        $html = $this->render('purchase/purchase_to_' . ($purchase->getMainPurchase() ? 'buy' : 'refund') . '_item.html.twig', ['purchase' => $purchase]);

        return new JsonResponse([
            'state' => 200,
            'isMain' => $purchase->getMainPurchase(),
            'html' => $html->getContent()
        ]);
    }
}

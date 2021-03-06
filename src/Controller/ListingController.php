<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\SecurityService;
use App\Form\ListingType;
use App\Form\PurchaseType;
use App\Entity\UserGroup;
use App\Entity\ListGroup;
use App\Entity\Listing;
use App\Entity\ListingItem;
use App\Entity\Purchase;

class ListingController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService) {
        $this->securityService = $securityService;
    }
    
    public function listingCreate(Request $request, ListGroup $listGroup) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $listing = new Listing();

        $form = $this->createForm(ListingType::class, $listing, [
            'method' => 'POST',
            'action' => $this->generateUrl('listing_create', ['id' => $listGroup->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $listing->setListingGroup($listGroup);
                $listing->setUser($this->getUser());

                $listing->setListingItemsListing();

                $em->persist($listing);
                $em->flush();

                $this->addFlash('success', 'Liste correctement créee !');

                return $this->redirect($this->generateUrl('list_group_view', ['idGroup' => $listGroup->getUserGroup()->getId(), 'idList' => $listGroup->getId()]));
            }

            $this->addFlash('warning', 'Erreur lors de la création de votr liste.');
        }

        return $this->render('listing/listing_create.html.twig', [ 
            'form' => $form->createView()
        ]);
    }

    /**
     * @ParamConverter("listGroup", options={"id": "idListGroup"})
     * @ParamConverter("listing", options={"id": "idListing"})
     */
    public function listingUpdate(Request $request, ListGroup $listGroup, Listing $listing) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ListingType::class, $listing, [
            'method' => 'POST',
            'action' => $this->generateUrl('listing_update', [
                'idListGroup' => $listGroup->getId(),
                'idListing' => $listing->getId()
            ])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $listing->setListingItemsListing();

                $em->persist($listing);
                $em->flush();

                $this->addFlash('success', 'Modification de la liste enregistrée.');

                return $this->redirect($this->generateUrl('list_group_view', [
                    'idGroup' => $listGroup->getUserGroup()->getId(),
                    'idList' => $listGroup->getId()
                ]));
            }

            $this->addFlash('warning', 'Erreur lors de la modification de votr liste.');

            return $this->redirect($this->generateUrl('list_group_view', [
                'idGroup' => $listGroup->getUserGroup()->getId(),
                'idList' => $listGroup->getId()
            ]));

        }

        return $this->render('listing/listing_update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function listingView(Listing $listing) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $html =  $this->render('listing/listing_view.html.twig', [
            'listing' => $listing
        ]);

        return new JsonResponse([
            'state' => 'ok',
            'username' => $listing->getUser()->getUsername(),
            'html' => $html->getContent()
        ]);
    }

    public function listingItemPurchase(Request $request, ListingItem $listingItem) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();
        
        $purchase = new Purchase();
        
        $form = $this->createForm(PurchaseType::class, $purchase, [
            'method' => 'POST',
            'action' => $this->generateUrl('listing_item_purchase', ['id' => $listingItem->getId()]),
            'max' => $listingItem->getMaxShare()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $purchase->setBuyer($this->getUser());
                $purchase->setListingItem($listingItem);
                $purchase->setMainPurchase($listingItem->isFirstPurchase());
                $purchase->setState(false);

                $em->persist($purchase);
                $em->flush();

                $this->addFlash('success', 'Prise en charge confirmé.');

                return $this->redirect($this->generateUrl('list_group_view', [
                    'idGroup' => $listingItem->getListing()->getListingGroup()->getUserGroup()->getId(), 
                    'idList' => $listingItem->getListing()->getListingGroup()->getId()
                ]));
            }
        }

        $html = $this->render('listing/listing_item_purchase.html.twig', [
            'form' => $form->createView(),
            'listingItem' => $listingItem
        ]);

        return new JsonResponse([
            'state' => 'ok',
            'itemName' => $listingItem->getName(),
            'html' => $html->getContent()
        ]);
    }
}

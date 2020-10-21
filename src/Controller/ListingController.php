<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\ListingType;
use App\Entity\UserGroup;
use App\Entity\ListGroup;
use App\Entity\Listing;
use App\Entity\ListingItem;

class ListingController extends AbstractController
{
    
    public function listingCreate(Request $request, ListGroup $listGroup) {
        $em = $this->getDoctrine()->getManager();

        $listing = new Listing();

        $item = new ListingItem();
        $item->setName('Test');
        $listing->addListingItem($item);

        $form = $this->createForm(ListingType::class, $listing, [
            'method' => 'POST',
            'action' => $this->generateUrl('listing_create', ['id' => $listGroup->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $listing->setListingGroup($listGroup);
                $listing->setUser($this->getUser());

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
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\SecurityService;
use App\Form\ListGroupType;
use App\Entity\UserGroup;
use App\Entity\ListGroup;
use App\Entity\Listing;

class ListController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService) {
        $this->securityService = $securityService;
    }

    /**
     * @ParamConverter("userGroup", options={"id": "idGroup"})
     * @ParamConverter("listGroup", options={"id": "idList"})
     */
    public function listGroupView(UserGroup $userGroup, ListGroup $listGroup) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (!$this->securityService->isUserGroupMember($this->getUser(), $userGroup))
            $this->denyAccessUnlessGranted('');

        return $this->render('list/list_group_view.html.twig', [
            'listGroup' => $listGroup
        ]);
    }

    public function listGroupCreate(Request $request, UserGroup $userGroup)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $listGroup = new ListGroup();

        $form = $this->createForm(ListGroupType::class, $listGroup, [
            'method' => 'POST',
            'action' => $this->generateUrl('list_group_create', ['id' => $userGroup->getId()])
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $listGroup->setUserGroup($userGroup);

                $em->persist($listGroup);
                $em->flush();

                $this->addFlash('success', 'Liste correctement crée.');

                return $this->redirect($this->generateUrl('list_group_view', ['idGroup' => $userGroup->getId(), 'idList' => $listGroup->getId()]));
            }

            $this->addFlash('warning', 'Erreur lors de la création de la liste, veuillez réessayer dans quelques minutes. Si le problème persiste, veuillez contacter un administrateur.');

            return $this->redirect($this->generateUrl('user_group_view', ['id' => $userGroup->getId()]));
        }

        return $this->render('list/list_group_create.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

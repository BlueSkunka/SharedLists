<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\SecurityService;
use App\Form\UserGroupType;
use App\Form\UserGroupRequestType;
use App\Entity\UserGroup;
use App\Entity\UserGroupRequest;

class GroupController extends AbstractController
{
    private $securityService;

    public function __construct(SecurityService $securityService) {
        $this->securityService = $securityService;
    }

    public function groups()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('group/index.html.twig', [
        ]);
    }

    public function newUserGroup(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $group = new UserGroup();

        $form = $this->createForm(UserGroupType::class, $group, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_group_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $group->setCreator($this->getUser());

                $em->persist($group);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Groupe crée! Vous pouvez dès maintenant ajouter des membres ou créer une nouvelle liste.'
                );

                return $this->redirect($this->generateUrl('groups'));
            } else {
                $this->addFlash(
                    'danger',
                    'Erreur lors de la création du groupe. Veuillez réessayer dans quelques minutes ou contacter l\'administrateur.'
                );

                return $this->redirect($this->generateUrl('groups'));
            }
        }
        
        return $this->render('group/group_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function viewUserGroup(UserGroup $userGroup) {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if (!$this->securityService->isUserGroupMember($this->getUser(), $userGroup))
            $this->denyAccessUnlessGranted('');

        return $this->render('group/group_view.html.twig', [
            'group' => $userGroup
        ]);
    }

    public function userGroupRequest(Request $request, UserGroup $userGroup) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $userGroupRequest = new UserGroupRequest();

        $form = $this->createForm(UserGroupRequestType::class, $userGroupRequest, [
            'method' => 'POST',
            'action' => $this->generateUrl('user_group_request', ['id' => $userGroup->getId()]),
            'friends' => $this->getUser()->getFriends()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $userGroupRequest->setUserGroup($userGroup);
                $userGroupRequest->setSender($this->getUser());
                $userGroupRequest->setDate(new \DateTime());

                $em->persist($userGroupRequest);
                $em->flush();

                $this->addFlash('success', 'Invitation correctement envoyée.');

                return $this->redirect($this->generateUrl('user-group-view', ['id' => $userGroupRequest->getId()]));
            }

            $this->addFlash('warning', 'Erreur lors du traitement. Veuillez réessayer dans quelques minutes. Si le problème persiste, veuillez contacter un administrateur.');

            return $this->redirect($this->generateUrl('user_group_view', ['id' => $userGroup->getId()]));
        }

        return $this->render('group/user_group_request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @ParamConverter("userGroup", options={"id": "idGroup"})
     * @ParamConverter("userGroupRequest", options={"id": "idRequest"})
     */
    public function userGroupRequestResponse(UserGroup $userGroup, UserGroupRequest $userGroupRequest, string $state)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em = $this->getDoctrine()->getManager();

        $redirectPath = "";
        $params = [];

        if ("accept" == $state) {
            $userGroupRequest->setState(true);

            $userGroup->addMember($this->getUser());

            $em->persist($userGroup);

            $redirectPath = "user_group_view";
            $params = ['id' => $userGroup->getId()];
        } else {
            $userGroupRequest->setState(false);

            $redirectPath = "groups";
        }

        $em->persist($userGroupRequest);
        $em->flush();

        return $this->redirect($this->generateUrl($redirectPath, $params));
    }

}

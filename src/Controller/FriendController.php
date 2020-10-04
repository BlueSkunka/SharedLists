<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\FriendRequest;
use App\Entity\User;
use App\Form\FriendRequestType;

class FriendController extends AbstractController
{
    public function friends()
    {
        return $this->render('friend/index.html.twig', [
        ]);
    }

    public function newFriend(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $friendRequest = new FriendRequest();

        $form = $this->createForm(FriendRequestType::class, $friendRequest, [
            'method' => 'POST',
            'action' => $this->generateUrl('friend_new')
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) { 
                $friend = $em->getRepository(User::class)->findOneBy(['username' => $form->get('name')->getData()]);

                if ($friend != null && $friend != $this->getUser()) {
                    $friendRequest->setDate(new \DateTime());
                    $friendRequest->setSender($this->getUser());
                    $friendRequest->setReceiver($friend); 
                    $em->persist($friendRequest);
                    $em->flush();

                    $this->addFlash('success', 'Requête d\'ami envoyée!');

                    return $this->redirect($this->generateUrl('friends'));
                }
                
            }

            $this->addFlash('warning' , 'Impossible de trouver cet utilisateur.');

            return $this->redirect($this->generateUrl('friends'));
        }

        return $this->render('friend/friend_request_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function friendRequestResponse(FriendRequest $friendRequest, String $state) {
        $em = $this->getDoctrine()->getManager();

        if ("accept" == $state) {
            $friendRequest->setState(true);

            $sender = $friendRequest->getSender();
            $sender->addFriend($this->getUser());

            $this->getUser()->addFriend($friendRequest->getSender());

            $em->persist($sender);
            $em->persist($this->getUser());

            $this->addFlash('success', 'Requête d\'ami acceptée.');
        } else {
            $friendRequest->setState(false);

            $this->addFlash('warning', 'Requête d\'ami refusée.');
        }

        $em->persist($friendRequest);
        $em->flush();

        return $this->redirect($this->generateUrl('friends'));
    }
}

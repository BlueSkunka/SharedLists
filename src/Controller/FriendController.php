<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\FriendRequest;
use App\Entity\User;
use App\Form\FriendRequestType;
use App\Service\NoticeService;

class FriendController extends AbstractController
{
    private $noticeService;

    public function __construct(NoticeService $noticeService) {
        $this->noticeService = $noticeService;
    }

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

                    $this->noticeService->createNotice([
                        'class' => 'info',
                        'type' => 'friend-request-new',
                        'user' => $friend,
                        'request-sender' => $this->getUser()->getUsername()
                    ]);

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

            // $this->noticeService->createNotice([
            //     'class' => 'info',
            //     'type' => 'friend-request-new',
            //     'user' =>  $this->getUser(),
            //     'request-receiver' => $friendRequest->getReceiver()->getUsername()
            // ]);

            $em->persist($friendRequest);
            $em->flush();

            return new JsonResponse([
                'statut' => 'ok',
                'username' => $sender->getUsername()
            ]);
        } else {
            $friendRequest->setState(false);

            // $this->noticeService->createNotice([
            //     'class' => 'info',
            //     'type' => 'friend-request-new',
            //     'user' =>  $this->getUser(),
            //     'request-receiver' => $friendRequest->getReceiver()->getUsername()
            // ]);

            $em->persist($friendRequest);
            $em->flush();

            return new JsonResponse(['statut' => 'ok']);
        }
    }

    public function friendRequestRemove(FriendRequest $friendRequest) {
        $em = $this->getDoctrine()->getManager();

        $em->remove($friendRequest);
        $em->flush();

        return new JsonResponse([
            'statut' => 'ok'
        ]);
    }
}

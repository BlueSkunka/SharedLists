<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Notice;
use App\Entity\NoticeClass;
use Symfony\Component\Security\Core\Security;

class NoticeService {

    private $em;

    private $options;
    
    public function __construct(EntityManagerInterface $entityManagerInterface) {
        $this->em = $entityManagerInterface;
    }

    public function createNotice(Array $options) {
        $this->options = $options;

        $notice = new Notice();

        $notice->setUser($this->options['user']);
        $notice->setNoticeClass($this->getNoticeClass());
        $notice->setText($this->getNoticeText());

        $this->em->persist($notice);
        $this->em->flush();
    }

    public function getNoticeClass() {
        return $this->em->getRepository(NoticeClass::class)->findOneBy(['name' => $this->options['class']]);
    }

    public function getNoticeText() {
        switch ($this->options['type']) {
            case 'friend-request-new':
                return "Vous avez une nouvelle requête d'ami de " . $this->options['request-sender'];
                break;
        }
    }
}
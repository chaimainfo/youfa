<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Zend\EventManager\Event;

class LoginListener
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user->setDerniereCnx(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }
}
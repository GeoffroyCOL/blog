<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * onSecurityInteractivelogin
     *
     * @param  InteractiveLoginEvent $event
     * @return void
     */
    public function onSecurityInteractivelogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user->setConnectedAt(new \DateTime);
        $this->manager->flush();
    }
}

<?php

namespace App\Membre;


use App\Entity\Membre;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Class MembreSuscriber
 * @package App\Membre
 */
class MembreSuscriber implements EventSubscriberInterface
{

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * MembreSuscriber constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin'
        ];
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        # Récupération de l'utilisateur
        $membre = $event->getAuthenticationToken()->getUser();

        if($membre instanceof Membre)
        {
            # On met à jour la dernière date de connexion
            $membre->setDerniereConnexion();
            $this->manager->flush();
        }

    }
}
<?php
namespace App\Newsletter;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Annotation\Route;


class NewsletterSuscriber implements EventSubscriberInterface
{
    private $session;

    /**
     * NewsletterSuscriber constructor.
     * @param $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST  => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    /**
     * @param GetResponseEvent $event
     * @Route(/suscribe, name="newsletter.suscribe")
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        # Je m'assure que la requête vient de l'utilisateur
        # et non de symfony
        if(!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()){
            return;
        }
        # Verifier si l'utilisateur est connecté
        # Imcrémentation du nombre de pages visitées par l'utilisateur
        $this->session->set('countVisitedPages', $this->session->get('countVisitedPages', 0)+1);

        # Inviter l'utilisateur
        if($this->session->get('countVisitedPages') === 3){

            $this->session->set('invitedUserModal', true);
        }
        dump($this->session->get('countVisitedPages'));
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->session->set('invitedUserModal', false);
    }

}
<?php

namespace Tutto\Bundle\UtilBundle\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Exception;

/**
 * Class SecurityContextSubscriber
 * @package Tutto\Bundle\UtilBundle\Form\Subscriber
 */
class SecurityContextSubscriber implements EventSubscriberInterface {
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event) {
        $session = $this->request->getSession();

        /** @var Exception $error */
        if ($this->request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $this->request->attributes->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error    = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $username = $session->get(SecurityContextInterface::LAST_USERNAME);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        }

        if ($error) {
            $event->getForm()->addError(new FormError($error->getMessage()));
        }

        $event->setData(array_replace((array) $event->getData(), array(
            '_username' => $username
        )));
    }

    /**
     * @return Request
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }
}
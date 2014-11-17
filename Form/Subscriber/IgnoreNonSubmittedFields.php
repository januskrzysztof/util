<?php

namespace Tutto\Bundle\UtilBundle\Form\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Class IgnoreNonSubmittedFields
 * @package Tutto\Bundle\UtilBundle\Form\Subscriber
 */
class IgnoreNonSubmittedFields implements EventSubscriberInterface {
    /**
     * @return array
     */
    public static function getSubscribedEvents() {
        return [FormEvents::PRE_SUBMIT => 'preSubmit'];
    }

    /**
     * @param FormEvent $event
     */
    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        foreach ($form->all() as $name => $child) {
            if (!isset($data[$name])) {
                $form->remove($name);
            }
        }

    }
}
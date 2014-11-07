<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Tutto\Bundle\UtilBundle\Logic\ProcessForm\Event;
use Exception;

use Tutto\Bundle\UtilBundle\Repository\AbstractEntityRepository;

/**
 * Class ProcessForm
 * @package Tutto\Bundle\UtilBundle\Logic
 */
class ProcessForm {
    const PRE_REQUEST         = 'preRequest';
    const POST_UPDATE         = 'postUpdate';
    const PRE_UPDATE          = 'preUpdate';
    const ON_RENDER           = 'onRender';
    const ON_EXCEPTION        = 'onException';
    const ON_FORM_ERROR       = 'onFormError';
    const PRE_FORM_VALIDATE   = 'preFormValidate';
    const POST_FORM_VALIDATE  = 'postFormValidate';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher) {
        $this->em = $em;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param FormInterface $form
     * @param $entity
     * @param Request $request
     * @return array
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws MappingException
     */
    public function processForm(FormInterface $form, Request $request, $entity = null) {
        $dispatcher = $this->eventDispatcher;
        $event      = new Event($form, $entity, $request);

        if ($dispatcher->hasListeners(self::PRE_REQUEST)) {
            $dispatcher->dispatch(self::PRE_REQUEST, $event);
        }

        if ($event->isPropagationStopped()) {
            return $this->stopAndReturnResponse($event);
        }

        if ($request->isMethod('post')) {
            if ($dispatcher->hasListeners(self::PRE_FORM_VALIDATE)) {
                $dispatcher->dispatch(self::PRE_FORM_VALIDATE, $event);
            }

            if ($event->isPropagationStopped()) {
                return $this->stopAndReturnResponse($event);
            }

            if ($form->handleRequest($request)->isValid()) {
                if ($entity === null) {
                    $entity = $form->getData();
                    $event->setEntity($entity);
                }

                /** Dispatch POST_FORM_VALIDATE events */
                if ($dispatcher->hasListeners(self::POST_FORM_VALIDATE)) {
                    $dispatcher->dispatch(self::POST_FORM_VALIDATE, $event);
                    $repository = $event->getRepository();
                } else {
                    $repository = null;
                }

                if ($event->isPropagationStopped()) {
                    return $this->stopAndReturnResponse($event);
                }

                /**
                 * Check if "entity repository" is set. If not, then automatically set by entity class name.
                 * If entity was not found then throws EntityNotFoundException.
                 */
                if (!isset($repository)) {
                    try {
                        if ($entity === null || !$this->em->getMetadataFactory()->getMetadataFor(get_class($entity))) {
                            throw new EntityNotFoundException();
                        }
                    } catch (MappingException $ex) {
                        throw $ex;
                    }
                }

                $this->em->beginTransaction();
                try {
                    /** Dispatch PRE_UPDATE events */
                    if ($dispatcher->hasListeners(self::PRE_UPDATE)) {
                        $dispatcher->dispatch(self::PRE_UPDATE, $event);
                    }

                    if ($event->isPropagationStopped()) {
                        return $this->stopAndReturnResponse($event);
                    }

                    if ($repository instanceof AbstractEntityRepository) {
                        /** @var AbstractEntityRepository $repository */
                        $repository->update($event->getEntity());
                    } else {
                        $this->em->persist($event->getEntity());
                        $this->em->flush();
                    }

                    /** Dispatch POST_UPDATE events */
                    if ($dispatcher->hasListeners(self::POST_UPDATE)) {
                        $dispatcher->dispatch(self::POST_UPDATE, $event);

                        $this->em->commit();
                        if ($event->getResponse()) {
                            return $event->getResponse();
                        }
                    } else {
                        $this->em->commit();
                    }

                    if ($event->isPropagationStopped()) {
                        return $this->stopAndReturnResponse($event);
                    }
                } catch (Exception $ex) {
                    $this->em->rollback();
                    if ($dispatcher->hasListeners(self::ON_EXCEPTION)) {
                        $event->setException($ex);
                        $dispatcher->dispatch(self::ON_EXCEPTION, $event);
                    }

                    if ($event->isPropagationStopped()) {
                        return $this->stopAndReturnResponse($event);
                    }
                }
            } else {
                if ($dispatcher->hasListeners(self::ON_FORM_ERROR)) {
                    $dispatcher->dispatch(self::ON_FORM_ERROR, $event);
                }

                if ($event->isPropagationStopped()) {
                    return $this->stopAndReturnResponse($event);
                }
            }
        }

        /** Dispatch ON_RENDER events */
        if ($dispatcher->hasListeners(self::ON_RENDER)) {
            $dispatcher->dispatch(self::ON_RENDER, $event);
            if ($event->getResponse()) {
                return $event->getResponse();
            }
        }

        return $this->stopAndReturnResponse($event);
    }

    /**
     * @param EventSubscriberInterface $subscriber
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber) {
        $this->eventDispatcher->addSubscriber($subscriber);
    }

    /**
     * @param string $eventName
     * @param callable $listener
     */
    public function addEventListener($eventName, $listener) {
        $this->eventDispatcher->addListener($eventName, $listener);
    }

    /**
     * @param Event $event
     * @return array|Response
     */
    private function stopAndReturnResponse(Event $event) {
        if ($event->getResponse() !== null) {
            return $event->getResponse();
        } else {
            return [
                'form'      => $event->getForm()->createView(),
                'entity'    => $event->getEntity(),
                'exception' =>$event->getException(),
            ];
        }
    }
}
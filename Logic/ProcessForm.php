<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Exception;

use Tutto\Bundle\UtilBundle\Repository\AbstractEntityRepository;

/**
 * Class ProcessForm
 * @package Tutto\Bundle\UtilBundle\Logic
 */
class ProcessForm {
    const PRE_HANDLE_REQUEST  = 'preHandleRequest';
    const POST_HANDLE_REQUEST = 'postHandleRequest';
    const POST_UPDATE         = 'postUpdate';
    const PRE_UPDATE          = 'preUpdate';
    const ON_RENDER           = 'onRender';

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
    public function processForm(FormInterface $form, $entity, Request $request) {
        $dispatcher = $this->eventDispatcher;
        $event      = new Event($form, $entity, $request);

        if ($dispatcher->hasListeners(self::PRE_HANDLE_REQUEST)) {
            $dispatcher->dispatch(self::PRE_HANDLE_REQUEST, $event);
        }

        if ($request->isMethod('post')) {
            if ($form->handleRequest($request)->isValid()) {
                /** Dispatch POST_HANDLE_REQUEST events */
                if ($dispatcher->hasListeners(self::POST_HANDLE_REQUEST)) {
                    $dispatcher->dispatch(self::POST_HANDLE_REQUEST, $event);
                    $repository = $event->getRepository();
                } else {
                    $repository = null;
                }

                /**
                 * Check if "entity repository" is set. If not, then automatically set by entity class name.
                 * If entity was not found then throws EntityNotFoundException.
                 */
                if (!isset($repository)) {
                    try {
                        if (!$this->em->getMetadataFactory()->getMetadataFor(get_class($entity))) {
                            throw new EntityNotFoundException();
                        }
                    } catch (MappingException $ex) {
                        throw $ex;
                    }
                }

                $this->em->beginTransaction();
                try {
                    $data = $form->getData();

                    /** Dispatch PRE_UPDATE events */
                    if ($dispatcher->hasListeners(self::PRE_UPDATE)) {
                        $dispatcher->dispatch(self::PRE_UPDATE, $event);
                    }

                    if ($repository instanceof AbstractEntityRepository) {
                        /** @var AbstractEntityRepository $repository */
                        $repository->update($data);
                    } else {
                        $this->em->persist($entity);
                        $this->em->flush($entity);
                    }

                    $this->em->commit();

                    /** Dispatch POST_UPDATE events */
                    if ($dispatcher->hasListeners(self::POST_UPDATE)) {
                        $dispatcher->dispatch(self::POST_UPDATE, $event);
                        if ($event->getResponse()) {
                            return $event->getResponse();
                        }
                    }
                } catch (Exception $ex) {
                    $this->em->rollback();
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

        return [
            'form'   => $form->createView(),
            'entity' => $entity
        ];
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
}
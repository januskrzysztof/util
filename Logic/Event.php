<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\Event as BaseEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Event
 * @package Tutto\Bundle\UtilBundle\Logic
 */
class Event extends BaseEvent {
    /**
     * @var Response
     */
    private $response;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var mixed
     */
    private $entity;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param FormInterface $form
     * @param $entity
     * @param Request $request
     */
    public function __construct(FormInterface $form, $entity, Request $request) {
        $this->setEntity($entity);
        $this->setForm($form);
        $this->setRequest($request);
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response) {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getEntity() {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     */
    public function setEntity($entity) {
        $this->entity = $entity;
    }

    /**
     * @return Response
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * @return FormInterface
     */
    public function getForm() {
        return $this->form;
    }

    /**
     * @param FormInterface $form
     */
    public function setForm(FormInterface $form) {
        $this->form = $form;
    }

    /**
     * @return EntityRepository
     */
    public function getRepository() {
        return $this->repository;
    }

    /**
     * @param EntityRepository $repository
     */
    public function setRepository(EntityRepository $repository) {
        $this->repository = $repository;
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
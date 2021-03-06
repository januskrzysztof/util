<?php

namespace Tutto\Bundle\UtilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping\MappingException;
use LogicException;
use Exception;

use Tutto\Bundle\UtilBundle\Logic\ProcessForm;
use Tutto\Bundle\UtilBundle\Logic\ProcessForm\Event;

/**
 * Class AbstractController
 * @package Tutto\Bundle\UtilBundle\Controller
 */
abstract class AbstractController extends Controller {
    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR   = 'error';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO    = 'info';

    const MESSAGE_SUCCESS = 'messages.success';
    const MESSAGE_WARNING = 'messages.warning';
    const MESSAGE_ERROR   = 'messages.error';
    const MESSAGE_INFO    = 'messages.info';

    /**
     * @param string $type
     * @param string $message
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     */
    protected function addFlashMessage($type, $message, array $parameters = [], $domain = null, $locale = null) {
        if ($domain === null) {
            $domain = 'flash_bags';
        }

        $this->container->get('session')->getFlashBag()->add($type, $this->trans($message, $parameters, $domain, $locale));
    }

    /**
     * @param string $message
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     */
    protected function addFlashSuccess($message = self::MESSAGE_SUCCESS, array $parameters = [], $domain = null, $locale = null) {
        $this->addFlashMessage(self::TYPE_SUCCESS, $message, $parameters, $domain, $locale);
    }

    /**
     * @param string $message
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     */
    protected function addFlashError($message = self::TYPE_ERROR, array $parameters = [], $domain = null, $locale = null) {
        $this->addFlashMessage(self::TYPE_ERROR, $message, $parameters, $domain, $locale);
    }

    /**
     * @param string $message
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     */
    protected function addFlashWarning($message = self::TYPE_WARNING, array $parameters = [], $domain = null, $locale = null) {
        $this->addFlashMessage(self::TYPE_WARNING, $message, $parameters, $domain, $locale);
    }

    /**
     * @param string $message
     */
    protected function addFlashInfo($message = self::TYPE_INFO) {
        $this->addFlashMessage(self::TYPE_INFO, $message);
    }

    /**
     * @param string $id
     * @param array $parameters
     * @param null|string $domain
     * @param null|string $locale
     * @return string
     */
    protected function trans($id, array $parameters = [], $domain = null, $locale = null) {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param FormInterface|AbstractType$form
     * @param null $entity
     * @param Request $request
     * @return array
     * @throws MappingException
     * @throws EntityNotFoundException
     * @throws Exception
     */
    protected function processForm($form, $entity = null, Request $request = null) {
        if ($form instanceof AbstractType) {
            $form = $this->createForm($form, $entity);
        } elseif ($form instanceof FormInterface) {
            throw new LogicException('Form is not AbstractType nor FormInterface.');
        }

        $processForm = $this->container->get('tutto_util.process_form');

        $processForm->addEventListener(ProcessForm::ON_EXCEPTION, function (Event $event) {
            $this->addFlashError('process_form.on_exception');
        });

        $processForm->addEventListener(ProcessForm::ON_FORM_ERROR, function (Event $event) {
            $this->addFlashError('process_form.on_form_error');
        });

        $processForm->addEventListener(ProcessForm::POST_UPDATE, function (Event $event) {
           $this->addFlashSuccess('process_form.post_update');
        });

        return $processForm->processForm($form, $entity, $request);
    }
}
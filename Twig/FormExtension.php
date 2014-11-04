<?php

namespace Tutto\Bundle\UtilBundle\Twig;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormView;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleTest;
use Twig_SimpleFilter;

/**
 * Class FormExtension
 * @package Tutto\Bundle\UtilBundle\Twig
 */
class FormExtension extends Twig_Extension {
    /**
     * @return array
     */
    public function getFunctions() {
        return [
            new Twig_SimpleFunction('form_has_errors', [$this, 'form_has_errors'])
        ];
    }

    /**
     * @return array
     */
    public function getFilters() {
        return [
            new Twig_SimpleFilter('form_has_errors', [$this, 'form_has_errors'])
        ];
    }


    /**
     * @return array
     */
    public function getTests() {
        return [
            new Twig_SimpleTest('form_has_errors', [$this, 'form_has_errors'])
        ];
    }


    /**
     * @return FormExtension
     */
    public function form_has_errors(FormView $form) {
        if (isset($form->vars['errors'])) {
            /** @var FormErrorIterator $errors */
            $errors = $form->vars['errors'];
            return $errors->count() > 0;
        } else {
            return false;
        }
    }

    public function hasErrors(FormView $form) {
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tutto_util_form';
    }
}
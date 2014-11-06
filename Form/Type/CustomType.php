<?php

namespace Tutto\Bundle\UtilBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CustomType
 * @package Tutto\Bundle\UtilBundle\Form\Type
 */
class CustomType extends AbstractType {
    /**
     * @var string
     */
    protected $name;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var array
     */
    protected $defaults;

    /**
     * @var string
     */
    protected $parent;

    /**
     * @param string $name
     * @param callable $callback
     * @param array $defaults
     * @param string $parent
     */
    function __construct($name, $callback, array $defaults = [], $parent = 'form') {
        $this->name     = $name;
        $this->callback = $callback;
        $this->defaults = $defaults;
        $this->parent   = $parent;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        call_user_func_array($this->callback, [$builder, $options]);
    }

    /**
     * @return mixed
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults($this->defaults);
    }

    /**
     * @return string The name of this type
     */
    public function getName() {
        return $this->name;
    }
}
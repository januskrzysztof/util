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
     * @param string $name
     * @param callable $callback
     * @param array $defaults
     */
    function __construct($name, $callback, array $defaults = []) {
        $this->name = $name;
        $this->callback = $callback;
        $this->defaults = $defaults;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        call_user_func_array($this->callback, [$builder, $options]);
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
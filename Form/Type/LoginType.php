<?php

namespace Tutto\Bundle\UtilBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tutto\Bundle\UtilBundle\Entity\User;
use Tutto\Bundle\UtilBundle\Form\Subscriber\SecurityContextSubscriber;

/**
 * Class LoginType
 * @package Tutto\Bundle\UtilBundle\Form\Type
 */
class LoginType extends AbstractType {
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
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add(
            '_username',
            'email',
            [
                'label' => 'email.l',
                'attr'  => [
                    'placeholder' => 'email.l'
                ]
            ]
        );

        $builder->add(
            '_password',
            'password',
            [
                'label' => 'login.password',
                'attr'  => [
                    'placeholder' => 'login.password'
                ]
            ]
        );

        $builder->add('_remember_me', 'checkbox', array(
            'label'    => 'login.remember_me',
            'required' => false
        ));

        $builder->addEventSubscriber(new SecurityContextSubscriber($this->request));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return '';
    }
}
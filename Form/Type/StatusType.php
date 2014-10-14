<?php

namespace Tutto\Bundle\UtilBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tutto\Bundle\UtilBundle\Logic\Status;

/**
 * Class StatusType
 * @package Tutto\Bundle\UtilBundle\Form\Type
 */
class StatusType extends AbstractType {
    /**
     * @return string
     */
    public function getParent() {
        return 'choice';
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults([
            'required' => true,
            'choices'  => [
                Status::ENABLED => 'status.enabled',
                Status::DISABLED => 'status.disabled',
                Status::ARCHIVED => 'status.archived'
            ]
        ]);
    }

    /**
     * @return string The name of this type
     */
    public function getName() {
        return 'tutto_util_status';
    }
}
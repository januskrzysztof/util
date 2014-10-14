<?php

namespace Tutto\Bundle\UtilBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Class LayoutResolver
 * @package Tutto\Bundle\UtilBundle\Twig
 */
class LayoutResolver extends Twig_Extension {
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $baseLayout;

    /**
     * @var string
     */
    private $ajaxLayout;

    /**
     * @var string
     */
    private $emptyLayout;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container   = $container;
        $config            = $this->container->getParameter('tutto_util');
        $this->baseLayout  = $config['layout_resolver']['base_layout'];
        $this->ajaxLayout  = $config['layout_resolver']['ajax_layout'];
        $this->emptyLayout = $config['layout_resolver']['empty_layout'];
    }

    /**
     * @return array
     */
    public function getFunctions() {
        return [new Twig_SimpleFunction('layoutResolver', [$this, 'layoutResolver'])];
    }

    /**
     * @return LayoutResolver
     */
    public function layoutResolver() {
        return $this;
    }

    /**
     * @param string|null $defaultLayout
     * @param Request $request
     * @return string
     */
    public function resolve($defaultLayout  = null, Request $request = null) {
        if ($request === null) {
            $request = $this->container->get('request');
        }

        if ($request->isXmlHttpRequest()) {
            if ($request->get('_format') === 'ajax') {
                return $this->ajaxLayout;
            }
        } else {
            if ((boolean) $request->get('_partial')) {
                return $this->emptyLayout;
            }
        }

        return $defaultLayout !== null ? $defaultLayout : $this->baseLayout;
    }

    /**
     * @return string
     */
    public function getName() {
        return 'tutto_util_layoutResolver';
    }
}
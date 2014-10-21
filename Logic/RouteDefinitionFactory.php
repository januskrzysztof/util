<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RouteDefinitionFactory
 * @package Tutto\Bundle\UtilBundle\Logic
 */
class RouteDefinitionFactory {
    /**
     * @var Router
     */
    private $router;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @param Router $router
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function __construct(Router $router, PropertyAccessorInterface $propertyAccessor) {
        $this->router           = $router;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @param string $routeName
     * @param array $definition
     * @param array $params
     * @param bool $type
     * @return RouteDefinition
     */
    public function create($routeName, $definition = ['id' => 'id'], $params = [], $type = UrlGeneratorInterface::ABSOLUTE_PATH) {
        return new RouteDefinition(
            $this->router,
            $this->propertyAccessor,
            $routeName,
            $definition,
            $params,
            $type
        );
    }
}
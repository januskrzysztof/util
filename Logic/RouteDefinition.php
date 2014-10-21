<?php

namespace Tutto\Bundle\UtilBundle\Logic;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RouteDefinition
 * @package Tutto\Bundle\UtilBundle\Logic
 */
class RouteDefinition {
    /**
     * @var Router
     */
    private $router;

    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var string
     */
    private $routeName;

    /**
     * @var array
     */
    private $routeDefinition = [];

    /**
     * @var array
     */
    private $additionalRouteParams = [];

    /**
     * @var bool
     */
    private $type = UrlGeneratorInterface::ABSOLUTE_PATH;

    /**
     * @param Router $router
     * @param PropertyAccessorInterface $propertyAccessor
     * @param string $routeName
     * @param array $routeDefinition
     * @param array $additionalRouteParams
     * @param bool $type
     */
    public function __construct(
        Router $router,
        PropertyAccessorInterface $propertyAccessor,
        $routeName,
        array $routeDefinition = [],
        array $additionalRouteParams = [],
        $type = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        $this->router                = $router;
        $this->propertyAccessor      = $propertyAccessor;
        $this->routeName             = $routeName;
        $this->routeDefinition       = $routeDefinition;
        $this->additionalRouteParams = $additionalRouteParams;
        $this->type                  = $type;

    }

    /**
     * @param mixed $data
     * @return string
     */
    public function generate($data = null) {
        $routeParams = [];
        foreach ($this->routeDefinition as $key => $value) {
            if (is_int($key)) {
                $key = $value;
            }

            $routeParams[$key] = $this->propertyAccessor->getValue($data, $value);
        }

        $routeParams = array_merge($this->additionalRouteParams, $routeParams);

        return $this->router->generate($this->routeName, $routeParams, $this->type);
    }
}
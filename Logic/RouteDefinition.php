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

    /**
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter($router) {
        $this->router = $router;
    }

    /**
     * @return PropertyAccessorInterface
     */
    public function getPropertyAccessor() {
        return $this->propertyAccessor;
    }

    /**
     * @param PropertyAccessorInterface $propertyAccessor
     */
    public function setPropertyAccessor($propertyAccessor) {
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @return string
     */
    public function getRouteName() {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName) {
        $this->routeName = $routeName;
    }

    /**
     * @return array
     */
    public function getRouteDefinition() {
        return $this->routeDefinition;
    }

    /**
     * @param array $routeDefinition
     */
    public function setRouteDefinition($routeDefinition) {
        $this->routeDefinition = $routeDefinition;
    }

    /**
     * @return array
     */
    public function getAdditionalRouteParams() {
        return $this->additionalRouteParams;
    }

    /**
     * @param array $additionalRouteParams
     */
    public function setAdditionalRouteParams($additionalRouteParams) {
        $this->additionalRouteParams = $additionalRouteParams;
    }

    /**
     * @return boolean
     */
    public function isType() {
        return $this->type;
    }

    /**
     * @param boolean $type
     */
    public function setType($type) {
        $this->type = $type;
    }
}
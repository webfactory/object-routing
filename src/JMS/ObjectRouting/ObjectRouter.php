<?php

/*
 * Copyright 2013 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\ObjectRouting;

use JMS\ObjectRouting\Metadata\ClassMetadata;
use JMS\ObjectRouting\Metadata\Driver\AttributeDriver;
use Metadata\Driver\DriverChain;
use Metadata\MetadataFactory;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ObjectRouter
{
    private $router;
    private $metadataFactory;
    private $accessor;
    private $expressionLanguage;

    public static function create(RouterInterface $router, ?ExpressionLanguage $expressionLanguage = null)
    {
        return new self(
            $router,
            new MetadataFactory(new DriverChain([
                new AttributeDriver(),
            ])),
            $expressionLanguage
        );
    }

    public function __construct(RouterInterface $router, MetadataFactoryInterface $metadataFactory, ?ExpressionLanguage $expressionLanguage = null)
    {
        $this->router = $router;
        $this->metadataFactory = $metadataFactory;
        $this->accessor = new PropertyAccessor();
        $this->expressionLanguage = $expressionLanguage ?? new ExpressionLanguage();
    }

    /**
     * Generates a path for an object.
     *
     * @param string $type
     * @param object $object
     * @param bool   $absolute
     *
     * @throws \InvalidArgumentException
     */
    public function generate($type, $object, $absolute = false, array $extraParams = [])
    {
        if (!\is_object($object)) {
            throw new \InvalidArgumentException(\sprintf('$object must be an object, but got "%s".', \gettype($object)));
        }

        /** @var $metadata ClassMetadata */
        $metadata = $this->metadataFactory->getMetadataForClass($object::class);
        if (null === $metadata) {
            throw new \RuntimeException(\sprintf('There were no object routes defined for class "%s".', $object::class));
        }

        if (!isset($metadata->routes[$type])) {
            throw new \RuntimeException(\sprintf('The object of class "%s" has no route with type "%s". Available types: %s', $object::class, $type, implode(', ', array_keys($metadata->routes))));
        }

        $route = $metadata->routes[$type];

        $params = $extraParams;
        foreach ($route['params'] as $k => $path) {
            $params[$k] = $this->accessor->getValue($object, $path);
        }

        foreach ($route['paramExpressions'] as $k => $expression) {
            if (!$expression instanceof ParsedExpression) {
                $expression = $this->expressionLanguage->parse($expression, ['this', 'params']);
                $metadata->routes[$type]['paramExpressions'][$k] = $expression;
            }
            $evaluated = $this->expressionLanguage->evaluate($expression, ['this' => $object, 'params' => $params]);
            if ('?' === $k[0]) {
                if (null === $evaluated) {
                    continue;
                }
                $params[substr($k, 1)] = $evaluated;
            } else {
                $params[$k] = $evaluated;
            }
        }

        return $this->router->generate($route['name'], $params, $absolute);
    }

    public function path($type, $object, array $extraParams = [])
    {
        return $this->generate($type, $object, false, $extraParams);
    }

    public function url($type, $object, array $extraParams = [])
    {
        return $this->generate($type, $object, true, $extraParams);
    }
}

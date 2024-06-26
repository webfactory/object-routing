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

namespace JMS\ObjectRouting\Metadata;

use Metadata\MergeableClassMetadata;
use Metadata\MergeableInterface;

class ClassMetadata extends MergeableClassMetadata
{
    public $routes = [];

    public function addRoute($type, $name, array $params = [], array $paramExpressions = [])
    {
        $this->routes[$type] = [
            'name' => $name,
            'params' => $params,
            'paramExpressions' => $paramExpressions,
        ];
    }

    public function merge(MergeableInterface $object): void
    {
        parent::merge($object);
        $this->routes = array_merge($this->routes, $object->routes);
    }

    public function serialize(): string
    {
        return serialize(
            [
                $this->routes,
                parent::serialize(),
            ]
        );
    }

    public function unserialize($str): void
    {
        list(
            $this->routes,
            $parentStr
        ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}

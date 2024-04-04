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

namespace JMS\ObjectRouting\Metadata\Driver;

use JMS\ObjectRouting\Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;

class AttributeDriver implements DriverInterface
{
    public function loadMetadataForClass(\ReflectionClass $class): ?ClassMetadata
    {
        $metadata = new ClassMetadata($class->name);

        $hasMetadata = false;
        foreach ($this->fetchAttributes($class) as $attribute) {
            $hasMetadata = true;
            $metadata->addRoute($attribute->type, $attribute->name, $attribute->params);
        }

        return $hasMetadata ? $metadata : null;
    }

    private function fetchAttributes(\ReflectionClass $class): array
    {
        $attributes = [];

        foreach ($class->getAttributes(\JMS\ObjectRouting\Attribute\ObjectRoute::class) as $attr) {
            $attributes[] = $attr->newInstance();
        }

        return $attributes;
    }
}

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

namespace JMS\ObjectRouting\Annotation;

/**
 * @deprecated
 * @Annotation
 * @NamedArgumentConstructor
 * @Target("CLASS")
 */
final class ObjectRoute extends \JMS\ObjectRouting\Attribute\ObjectRoute
{
    public function __construct(string $type, string $name, array $params = [])
    {
        trigger_deprecation('webfactory/object-routing', '1.7.0', 'Using annotations to configure object routes is deprecated. Use the %s attribute instead', \JMS\ObjectRouting\Attribute\ObjectRoute::class);
        parent::__construct($type, $name, $params);
    }
}

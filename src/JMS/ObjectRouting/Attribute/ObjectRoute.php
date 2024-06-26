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

namespace JMS\ObjectRouting\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
final class ObjectRoute
{
    /** @var string @Required */
    public $type;

    /** @var string @Required */
    public $name;

    /** @var array */
    public $params = [];

    /** @var array */
    public $paramExpressions = [];

    public function __construct(string $type, string $name, array $params = [], array $paramExpressions = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->params = $params;
        $this->paramExpressions = $paramExpressions;
    }
}

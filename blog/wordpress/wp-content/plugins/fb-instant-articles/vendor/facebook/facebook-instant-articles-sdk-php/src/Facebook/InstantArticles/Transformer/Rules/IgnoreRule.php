<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Rules;

use Facebook\InstantArticles\Elements\Element;
use Facebook\InstantArticles\Transformer\Getters\GetterFactory;
use Facebook\InstantArticles\Transformer\Getters\StringGetter;
use Facebook\InstantArticles\Transformer\Getters\ElementGetter;

class IgnoreRule extends ConfigurationSelectorRule
{
    public function __construct()
    {
    }

    public static function create()
    {
        return new IgnoreRule();
    }

    public static function createFrom($configuration)
    {
        return self::create()->withSelector($configuration['selector']);
    }

    public function getContextClass()
    {
        return $this->contextClass = Element::getClassName();
    }

    public function apply($transformer, $context, $element)
    {
        return $context;
    }

    public function loadFrom($configuration)
    {
        $this->selector = $configuration['selector'];
    }
}

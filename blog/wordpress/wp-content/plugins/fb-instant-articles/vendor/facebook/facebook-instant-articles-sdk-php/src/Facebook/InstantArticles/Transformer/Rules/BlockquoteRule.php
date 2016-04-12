<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Rules;

use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Elements\Blockquote;
use Facebook\InstantArticles\Transformer\Getters\GetterFactory;
use Facebook\InstantArticles\Transformer\Getters\StringGetter;
use Facebook\InstantArticles\Transformer\Getters\ChildrenGetter;

class BlockquoteRule extends ConfigurationSelectorRule
{
    public function __construct()
    {
    }

    public function getContextClass()
    {
        return InstantArticle::getClassName();
    }

    public static function create()
    {
        return new BlockquoteRule();
    }

    public static function createFrom($configuration)
    {
        return self::create()->withSelector($configuration['selector']);
    }

    public function apply($transformer, $instant_article, $element)
    {
        $blockquote = Blockquote::create();
        $instant_article->addChild($blockquote);
        $transformer->transform($blockquote, $element);
        return $instant_article;
    }

    public function loadFrom($configuration)
    {
        $this->selector = $configuration['selector'];
    }
}

<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Getters;

use Facebook\InstantArticles\Validators\Type;
use Symfony\Component\CssSelector\CssSelectorConverter;

class XpathGetter extends ChildrenGetter
{
    protected $attribute;

    public function createFrom($properties)
    {
        if (isset($properties['selector'])) {
            $this->withSelector($properties['selector']);
        }
        if (isset($properties['attribute'])) {
            $this->withAttribute($properties['attribute']);
        }
    }

    public function withAttribute($attribute)
    {
        Type::enforce($attribute, Type::STRING);
        $this->attribute = $attribute;

        return $this;
    }

    public function get($node)
    {
        Type::enforce($node, 'DOMNode');
        $domXPath = new \DOMXPath($node->ownerDocument);
        $elements = $domXPath->query($this->selector, $node);

        if (!empty($elements) && $elements->item(0)) {
            $element = $elements->item(0);
            if ($this->attribute) {
                return $element->getAttribute($this->attribute);
            }
            return $element->textContent;
        }
        return null;
    }
}

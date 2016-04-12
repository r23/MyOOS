<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Rules;

use Symfony\Component\CssSelector\CssSelectorConverter;
use Facebook\InstantArticles\Transformer\Getters\GetterFactory;
use Facebook\InstantArticles\Transformer\Getters\StringGetter;
use Facebook\InstantArticles\Transformer\Getters\ChildrenGetter;
use Facebook\InstantArticles\Validators\Type;

abstract class ConfigurationSelectorRule extends Rule
{
    protected $selector;
    protected $properties = array();

    public function withSelector($selector)
    {
        $this->selector = $selector;
        return $this;
    }

    public function withProperty($property, $value)
    {
        if ($value) {
            $this->properties[$property] = GetterFactory::create($value);
        }
        return $this;
    }

    public function withProperties($properties, $configuration)
    {
        Type::enforceArrayOf($properties, Type::STRING);
        foreach ($properties as $property) {
            $this->withProperty(
                $property,
                self::retrieveProperty($configuration, $property)
            );
        }
    }

    public function matchesContext($context)
    {
        if (Type::is($context, $this->getContextClass())) {
            return true;
        }
        return false;
    }

    public function matchesNode($node)
    {
        if ($this->selector === 'html' && $node->nodeName === 'html') {
            return true;
        }

        $document = $node->ownerDocument;
        $domXPath = new \DOMXPath($document);

        if (substr($this->selector, 0, 1) === '/') {
            $xpath = $this->selector;
        } else {
            $converter = new CssSelectorConverter();
            $xpath = $converter->toXPath($this->selector);
        }

        $results = $domXPath->query($xpath);

        foreach ($results as $result) {
            if ($result === $node) {
                return true;
            }
        }
        return false;
    }

    public function findAll($node, $selector)
    {
        $domXPath = new \DOMXPath($node->ownerDocument);
        $converter = new CssSelectorConverter();
        $xpath = $converter->toXPath($selector);
        return $domXPath->query($xpath, $node);
    }

    public function getProperty($property_name, $node)
    {
        $value = null;
        if (isset($this->properties[$property_name])) {
            $value = $this->properties[$property_name]->get($node);
        }
        return $value;
    }

    public function getProperties()
    {
        return $this->properties;
    }
}

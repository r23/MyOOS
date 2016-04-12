<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Elements;

use Facebook\InstantArticles\Validators\Type;

/**
 * Class RelatedItem to represent each of the @see RelatedArticles
 */
class RelatedItem extends Element
{
    /**
     * @var string The related Article URL
     */
    private $url;

    /**
     * @var boolean If the article is sponsored
     */
    private $sponsored;

    /**
     * Private constructor.
     */
    private function __construct()
    {
    }

    /**
     * Factory method for the RelatedItem
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Sets the article URL
     * @param string The related article URL
     */
    public function withURL($url)
    {
        Type::enforce($url, Type::STRING);
        $this->url = $url;

        return $this;
    }

    /**
     * Makes this item to be an sponsored one
     */
    public function enableSponsored()
    {
        $this->sponsored = true;

        return $this;
    }

    /**
     * Makes this item to *NOT* be an sponsored one
     */
    public function disableSponsored()
    {
        $this->sponsored = false;

        return $this;
    }

    /**
     * @return string The RelatedItem url
     */
    public function getURL()
    {
        return $this->url;
    }

    /**
     * @return boolean true if it is sponsored, false otherwise.
     */
    public function isSponsored()
    {
        return $this->sponsored;
    }

    /**
     * Structure and create the full ArticleVideo in a XML format DOMElement.
     *
     * @param $document DOMDocument where this element will be appended. Optional
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        $element = $document->createElement('li');
        if ($this->sponsored) {
            $element->setAttribute('data-sponsored', 'true');
        }
        $element->appendChild(
            Anchor::create()->withHref($this->url)->toDOMElement($document)
        );


        return $element;
    }
}

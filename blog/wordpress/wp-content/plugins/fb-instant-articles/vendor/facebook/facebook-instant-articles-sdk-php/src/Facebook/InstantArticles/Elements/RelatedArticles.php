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
 * Class List that represents a simple HTML list
 *
 * Example Unordered:
 * <ul>
 *     <li>Dog</li>
 *     <li>Cat</li>
 *     <li>Fox</li>
 * </ul>
 *
 * Example Ordered:
 * <ol>
 *     <li>Groceries</li>
 *     <li>School</li>
 *     <li>Sleep</li>
 * </ol>
 */
class RelatedArticles extends Element
{
    /**
     * @var RelatedItem[] The related Articles
     */
    private $items = array();

    /**
     * @var string The title of the Related Articles content
     */
    private $title;

    /**
     * Private constructor.
     * @see List::create()
     */
    private function __construct()
    {
    }

    /**
     * Factory method for the RelatedArticles list
     * @return RelatedArticles the new instance of RelatedArticles
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Adds a new related article item
     * @param string The related article URL
     */
    public function addRelated($item)
    {
        Type::enforce($item, RelatedItem::getClassName());
        $this->items[] = $item;

        return $this;
    }

    /**
     * Sets the title of Related articles content block
     *
     * @param string the name of related articles block
     */
    public function withTitle($title)
    {
        Type::enforce($title, Type::STRING);
        $this->title = $title;

        return $this;
    }

    /**
     * @return RelatedItem[] The RelatedItem's
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param string the name of related articles block
     */
    public function getTitle()
    {
        return $this->title;
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

        $element = $document->createElement('ul');
        $element->setAttribute('class', 'op-related-articles');
        if ($this->title) {
            $element->setAttribute('title', $this->title);
        }

        if ($this->items) {
            foreach ($this->items as $item) {
                $element->appendChild($item->toDOMElement($document));
            }
        }

        return $element;
    }
}

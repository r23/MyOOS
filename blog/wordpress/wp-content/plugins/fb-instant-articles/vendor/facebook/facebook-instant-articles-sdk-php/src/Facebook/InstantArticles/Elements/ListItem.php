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
 * Example:
 *     <li>Dog</li>
 */
class ListItem extends TextContainer
{
    /**
     * @var TextContainer
     */
    private $text;

    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    /**
     * @param string|TextContainer $text the text that will be added to <li>
     */
    public function withText($text)
    {
        Type::enforce($text, array(TextContainer::getClassName(), Type::STRING));
        $this->text = $text;

        return $this;
    }

    /**
     * Overrides the appendText to make sure only one child will be setted on this ListItem.
     * If appendText is called multiple times, it will store only the last one.
     * @see ListItem::withText()
     *
     * @param string|TextContainer The content can be a string or a TextContainer.
     */
    public function appendText($child)
    {
        return $this->withText($child);
    }


    /**
     * @return string|TextContainer The text that was added thru @see ListItem::withText()
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Structure and create the full ListItem in a DOMElement.
     *
     * @param DOMDocument $document - The document where this element will be appended (optional).
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }
        $list_item = $document->createElement('li');

        if ($this->text) {
            if (Type::is($this->text, Type::STRING)) {
                $list_item->appendChild($document->createTextNode($this->text));
            } else {
                $list_item->appendChild($this->text->toDOMElement($document));
            }
        }

        return $list_item;
    }
}

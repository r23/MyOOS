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
class ListElement extends Element
{
    /**
     * @var boolean Checks if it is ordered or unordered
     */
    private $isOrdered;

    /**
     * @var ListItem[] Items of the list
     */
    private $items = array();

    /**
     * Private constructor.
     * @see List::createOrdered() and @see List::createUnordered().
     */
    private function __construct()
    {
    }

    /**
     * Factory method for an Ordered list
     * @return ListElement the new instance List as an ordered list
     */
    public static function createOrdered()
    {
        $list = new self();
        $list->enableOrdered();

        return $list;
    }

    /**
     * Factory method for an unrdered list
     * @return ListElement the new instance List as an unordered list
     */
    public static function createUnordered()
    {
        $list = new self();
        $list->disableOrdered();

        return $list;
    }

    /**
     * Adds a new item to the List
     *
     * @param ListItem The new item that will be pushed to the end of the list
     */
    public function addItem($new_item)
    {
        Type::enforce($new_item, array(ListItem::getClassName(), Type::STRING));
        if (Type::is($new_item, Type::STRING)) {
            $new_item = ListItem::create()->withText($new_item);
        }
        $this->items[] = $new_item;

        return $this;
    }

    /**
     * Sets all items of the list as the array on the parameter
     *
     * @param ListItem[] The new items. Replaces all items from the list
     */
    public function withItems($new_items)
    {
        Type::enforceArrayOf($new_items, array(ListItem::getClassName(), Type::STRING));
        foreach ($new_items as $new_item) {
            $this->addItem($new_item);
        }

        return $this;
    }

    /**
     * Makes the list become ordered
     */
    public function enableOrdered()
    {
        $this->isOrdered = true;

        return $this;
    }

    /**
     * Makes the list become unordered
     */
    public function disableOrdered()
    {
        $this->isOrdered = false;

        return $this;
    }

    /**
     * Auxiliary method to find the starting string
     */
    private static function startsWith($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }

    /**
     * @return string[] the list text items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return boolean if the list is ordered
     */
    public function isOrdered()
    {
        return $this->isOrdered;
    }

    /**
     * Structure and create the full Video in a XML format DOMElement.
     *
     * @param $document DOMDocument where this element will be appended. Optional
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        if ($this->isOrdered) {
            $element = $document->createElement('ol');
        } else {
            $element = $document->createElement('ul');
        }

        if ($this->items) {
            foreach ($this->items as $item) {
                if ($item) {
                    $element->appendChild($item->toDOMElement($document));
                }
            }
        }

        return $element;
    }
}

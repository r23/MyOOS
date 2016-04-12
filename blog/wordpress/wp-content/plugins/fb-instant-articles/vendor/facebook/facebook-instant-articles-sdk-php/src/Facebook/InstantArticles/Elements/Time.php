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
 * Class Time
 * This represents time of publishing (@see const PUBLISHED) or time of update
 * (@see const MODIFIED).
 *
 *
 * <time
 *     class="op-published"
 *     datetime={date('c', $this->time)}>
 *     {date('F jS, g:ia', $this->time)}
 * </time>
 *
 * or
 *
 * <time
 *     class="op-modified"
 *     datetime={date('c', $last_update)}>
 *     {date('F jS, g:ia', $last_update)}
 * </time>
 */
class Time extends Element
{
    /**
     * @const string Represents the type for when article was first published
     */
    const PUBLISHED = 'op-published';

    /**
     * @const string Represents the type for when article was last modified
     */
    const MODIFIED = 'op-modified';

    /**
     * @const string The date formater
     */
    const DATE_FORMAT = 'F jS, g:ia';

    /**
     * @var DateTime The date formater
     */
    private $date;

    /**
     * @var string The type of this Article time (MODIFIED or PUBLISHED)
     *
     * @see Time::MODIFIED
     * @see Time::PUBLISHED
     */
    private $type;

    /**
     * Private constructor. Should use @see Time::create();.
     */
    private function __construct()
    {
    }

    /**
     * @param string The type of this Article time (MODIFIED or PUBLISHED)
     * @see Time::MODIFIED
     * @see Time::PUBLISHED
     */
    public static function create($type)
    {
        $article_time = new self();
        return $article_time->withType($type);
    }

    /**
     * Overwrites the current type of time
     *
     * @param string The type of this Article time (MODIFIED or PUBLISHED)
     *
     * @see Time::MODIFIED
     * @see Time::PUBLISHED
     */
    public function withType($type)
    {
        Type::enforceWithin(
            $type,
            array(
                Time::MODIFIED,
                Time::PUBLISHED
            )
        );
        $this->type = $type;

        return $this;
    }

    /**
     * Overwrites the current date in the object
     *
     * @param DateTime The date formater
     */
    public function withDatetime($date)
    {
        Type::enforce($date, 'DateTime');
        $this->date = $date;

        return $this;
    }

    /**
     * @return DateTime The date
     */
    public function getDatetime()
    {
        return $this->date;
    }

    /**
     * @return string The time type
     *
     * @see Time::MODIFIED
     * @see Time::PUBLISHED
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Structure and create the full Time in a XML format DOMElement.
     *
     * @param $document DOMDocument where this element will be appended. Optional
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        $datetime = $this->date->format('c');
        $date = $this->date->format('F jS, g:ia');

        $element = $document->createElement('time');
        $element->setAttribute('class', $this->type);
        $element->setAttribute('datetime', $datetime);
        $element->appendChild($document->createTextNode($date));

        return $element;
    }
}

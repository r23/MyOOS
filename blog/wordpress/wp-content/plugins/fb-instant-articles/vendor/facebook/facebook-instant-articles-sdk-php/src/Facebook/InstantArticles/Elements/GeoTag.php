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
 * Class Map
 * This element Class holds map content for the articles.
 *
 * Example:
 *  <figure class="op-map">
 *    <script type="application/json" class="op-geotag">
 *      {
 *          "type": "Feature",
 *          "geometry": {
 *               "type": "Point",
 *               "coordinates": [23.166667, 89.216667]
 *          },
 *          "properties": {
 *               "title": "Jessore, Bangladesh",
 *               "radius": 750000,
 *               "pivot": true,
 *               "style": "satellite",
 *           }
 *       }
 *    </script>
 *  </figure>
 *
 */
class GeoTag extends Element
{
    /**
     * @var string The json geotag content inside the script geotag
     */
    private $script;

    /**
     * Private constructor.
     * @see GeoTag::create();.
     */
    private function __construct()
    {
    }

    /**
     * Factory method for the Map
     * @return GeoTag the new instance
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Sets the geotag on the image.
     *
     * @see {link:http://geojson.org/}
     */
    public function withScript($script)
    {
        Type::enforce($script, Type::STRING);
        $this->script = $script; // TODO Validate the json informed

        return $this;
    }

    /**
     * @return string Geotag json content unescaped
     */
    public function getScript()
    {
        return $this->script;
    }

    /**
     * Structure and create the full Map in a XML format DOMElement.
     *
     * @param $document DOMDocument where this element will be appended. Optional
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }
        $element = $document->createElement('script');
        $element->setAttribute('type', 'application/json');
        $element->setAttribute('class', 'op-geotag');

        // Required script field
        if ($this->script) {
            $element->appendChild($document->createTextNode($this->script));
        }

        return $element;
    }
}

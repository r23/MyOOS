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
 * Tracking code for your article
 *
 * Example:
 * <figure class="op-tracker">
 *     <iframe src="https://www.myserver.com/trackingcode"></iframe>
 * </figure>
 *
 * or
 *
 * <figure class="op-tracker">
 *    <iframe>
 *      <!-- Include full analytics code here -->
 *    </iframe>
 * </figure>
 *
 * @see {link:https://developers.intern.facebook.com/docs/instant-articles/reference/analytics}
 */
class Analytics extends Element
{
    /**
     * @var string The source of the content for your analytics code.
     */
    private $source;

    /**
     * @var \DOMNode The HTML of the content.
     */
    private $html;

    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    /**
     * Sets the source for the ad.
     *
     * @param string The source of the content for your ad.
     */
    public function withSource($source)
    {
        Type::enforce($source, Type::STRING);
        $this->source = $source;

        return $this;
    }

    /**
     * Sets the unescaped HTML of your ad.
     *
     * @param \DOMNode The unescaped HTML of your ad.
     */
    public function withHTML($html)
    {
        Type::enforce($html, 'DOMNode');
        $this->html = $html;

        return $this;
    }

    /**
     * Gets the source for the analytics.
     *
     * @return string The source of the content for your analytics.
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Gets the unescaped HTML of your analytics.
     *
     * @return \DOMNode The unescaped HTML of your analytics.
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Structure and create the full ArticleAd in a DOMElement.
     *
     * @param DOMDocument $document - The document where this element will be appended (optional).
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }
        $figure = $document->createElement('figure');
        $iframe = $document->createElement('iframe');

        $figure->appendChild($iframe);
        $figure->setAttribute('class', 'op-tracker');

        if ($this->source) {
            $iframe->setAttribute('src', $this->source);
        }

        // Analytics markup
        if ($this->html) {
            // Here we do not care about what is inside the iframe
            // because it'll be rendered in a sandboxed webview
            $this->dangerouslyAppendUnescapedHTML($iframe, $this->html);
        } else {
            $iframe->appendChild($document->createTextNode(''));
        }

        return $figure;
    }
}

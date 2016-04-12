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
 * Embeds content from social media
 *
 * Example:
 * <figure class="op-social">
 *   <iframe>
 *     <!-- Include Instagram embed code here -->
 *   </iframe>
 * </figure>
 *
 * @see {link:https://developers.intern.facebook.com/docs/instant-articles/reference/social}
 */
class SocialEmbed extends Element
{
    /**
     * @var Caption Descriptive text for your social embed.
     */
    private $caption;

    /**
     * @var \DOMNode The HTML of the content.
     */
    private $html;

    /**
     * @var string The source of the content for your social embed.
     */
    private $source;

    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    /**
     * Sets the caption for the social embed.
     *
     * @param Caption $caption - Descriptive text for your social embed.
     */
    public function withCaption($caption)
    {
        Type::enforce($caption, Caption::getClassName());
        $this->caption = $caption;

        return $this;
    }

    /**
     * Sets the source for the social embed.
     *
     * @param string $source - The source of the content for your social embed.
     */
    public function withSource($source)
    {
        Type::enforce($source, Type::STRING);
        $this->source = $source;

        return $this;
    }

    /**
     * Sets the unescaped HTML of your social embed.
     *
     * @param \DOMNode $html - The unescaped HTML of your social embed.
     */
    public function withHTML($html)
    {
        Type::enforce($html, 'DOMNode');
        $this->html = $html;

        return $this;
    }

    /**
     * @return Caption - The caption for social embed block
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return \DOMNode $html - The unescaped HTML of your social embed.
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @return string $source - The source of the content for your social embed.
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Structure and create the full SocialEmbed in a DOMElement.
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

        // Caption markup optional
        if ($this->caption) {
            $figure->appendChild($this->caption->toDOMElement($document));
        }

        if ($this->source) {
            $iframe->setAttribute('src', $this->source);
        }

        $figure->setAttribute('class', 'op-social');

        // SocialEmbed markup
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

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
 * Base class for components accepting formatted text. It can contain bold, italic and links.
 *
 * Example:
 * This is a <b>formatted</b> <i>text</i> for <a href="https://foo.com">your article</a>.
 *
 * @see {link:https://developers.intern.facebook.com/docs/instant-articles/reference/body-text}
 */
abstract class TextContainer extends Element
{
    /**
     * @var array The content is a list of strings and FormattingElements
     */
    private $textChildren = array();

    /**
     * Adds content to the formatted text.
     *
     * @param string|FormattedText The content can be a string or a FormattedText.
     */
    public function appendText($child)
    {
        Type::enforce($child, array(Type::STRING, FormattedText::getClassName()));
        $this->textChildren[] = $child;

        return $this;
    }

    /**
     * @return array<string|FormattedText> All text token for this text container.
     */
    public function getTextChildren()
    {
        return $this->textChildren;
    }

    /**
     * Structure and create the full text in a DOMDocumentFragment.
     *
     * @param DOMDocument $document - The document where this element will be appended (optional).
     */
    public function textToDOMDocumentFragment($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        $fragment = $document->createDocumentFragment();

        // Generate markup
        foreach ($this->textChildren as $content) {
            if (Type::is($content, Type::STRING)) {
                $text = $document->createTextNode($content);
                $fragment->appendChild($text);
            } else {
                $fragment->appendChild($content->toDOMElement($document));
            }
        }

        if (!$fragment->hasChildNodes()) {
            $fragment->appendChild($document->createTextNode(''));
        }

        return $fragment;
    }
}

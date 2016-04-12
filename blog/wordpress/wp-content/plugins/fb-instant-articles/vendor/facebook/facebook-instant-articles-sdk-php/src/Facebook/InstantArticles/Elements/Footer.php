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
 * Footer of the article.
 *
 * Example:
 * <body>
 *  <article>
 *    <footer>
 *      <aside>
 *        <p>The magazine thanks <a rel="facebook" href="...">The Rockefeller Foundation</a></p>
 *        <p>The magazine would also like to thank its readers.</p>
 *      </aside>
 *    </footer>
 *  </article>
 * </body>
 *
 * @see {link:https://developers.intern.facebook.com/docs/instant-articles/reference/footer}
 */
class Footer extends Element
{
    /**
     * @var string|Paragraph[] The text content of the credits
     */
    private $credits = array();

    /**
     * @var string Copyright information of the article
     */
    private $copyright;

    /**
     * @var RelatedArticles the related articles to be added to this footer. Optional
     */
    private $relatedArticles;

    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }

    /**
     * Sets the text content of the credits
     *
     * @param string|array<paragrah> $credits - A list of paragraphs or a single string for the content of the credit.
     */
    public function withCredits($credits)
    {
        Type::enforce($credits, array(Type::ARRAY_TYPE, Paragraph::getClassName(), Type::STRING));
        $this->credits = $credits;

        return $this;
    }

    /**
     * Adds a new Paragraph to the credits
     *
     * @param Paragrah $credit - One Paragraph to be added as a credit.
     */
    public function addCredit($credit)
    {
        Type::enforce($credit, Paragraph::getClassName());
        $this->credits[] = $credit;

        return $this;
    }

    /**
     * Sets the copyright information for the article.
     *
     * @param string $copyright - The copyright information.
     */
    public function withCopyright($copyright)
    {
        Type::enforce($copyright, Type::STRING);
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * Sets the related articles within the footer of the article.
     *
     * @param RelatedArticles $related_articles - The related articles
     */
    public function withRelatedArticles($related_articles)
    {
        Type::enforce($related_articles, RelatedArticles::getClassName());
        $this->relatedArticles = $related_articles;

        return $this;
    }

    /**
     * Gets the text content of the credits
     *
     * @return string|array<paragrah> $credits - A list of paragraphs or a single string for the content of the credit.
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * Gets the copyright information for the article.
     *
     * @return string $copyright - The copyright information.
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * Gets the related articles within the footer of the article.
     *
     * @return RelatedArticles $related_articles - The related articles
     */
    public function getRelatedArticles()
    {
        return $this->relatedArticles;
    }

    /**
     * Structure and create the full Footer in a DOMElement.
     *
     * @param DOMDocument $document - The document where this element will be appended (optional).
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }
        $footer = $document->createElement('footer');

        // Footer markup
        if ($this->credits) {
            $aside = $document->createElement('aside');
            if (is_array($this->credits)) {
                foreach ($this->credits as $paragraph) {
                    $aside->appendChild($paragraph->toDOMElement($document));
                }
            } else {
                $aside->appendChild($document->createTextNode($this->credits));
            }
            $footer->appendChild($aside);
        }

        if ($this->copyright) {
            $small = $document->createElement('small');
            $small->appendChild($document->createTextNode($this->copyright));
            $footer->appendChild($small);
        }

        if ($this->relatedArticles) {
            $footer->appendChild($this->relatedArticles->toDOMElement($document));
        }

        if (!$this->credits && !$this->copyright && !$this->relatedArticles) {
            $footer->appendChild($document->createTextNode(''));
        }

        return $footer;
    }
}

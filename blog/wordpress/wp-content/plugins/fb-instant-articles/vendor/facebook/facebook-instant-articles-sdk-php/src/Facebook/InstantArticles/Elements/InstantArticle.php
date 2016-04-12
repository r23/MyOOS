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
  * Class InstantArticle
  * This class holds the content of one InstantArticle and is children
  *
  *    <html>
  *        <head>
  *            ...
  *        </head>
  *        <body>
  *            <article>
  *                <header>
  *                    <figure>...</figure>
  *                    <h1>...</h1>
  *                    <time>...</time>
  *                </header>
  *                <contents...>
  *            </article>
  *        </body>
  *    </html>
  *
*/
class InstantArticle extends Element
{
    const CURRENT_VERSION = '1.0.2';

    /**
     * The meta properties that are used on <head>
     */
    private $metaProperties = array();

    /**
     * @var string The canonical URL for the Instant Article
     */
    private $canonicalURL;

    /**
     * @var string The markup version for this InstantArticle
     */
    private $markupVersion = 'v1.0';

    /**
     * @var boolean The ad strategy that will be used. True by default
     */
    private $isAutomaticAdPlaced = true;

    /**
     * @var string The charset that will be used. "utf-8" by default.
     */
    private $charset = 'utf-8';

    /**
     * @var string|null The style that will be applied to the article. Optional.
     */
    private $style;

    /**
     * @var ArticleHeader element to hold header content, like images etc
     */
    private $header;

    /**
     * @var ArticleFooter element to hold footer content.
     */
    private $footer;

    /**
     * @var Element[] of all elements an article can have.
     */
    private $children = array();

    /**
     * Factory method
     * @return InstantArticle object.
     */
    public static function create()
    {
        return new InstantArticle();
    }

    /**
     * Private constructor. It must be used the Factory method
     * @see InstantArticle#create() For building objects
     * @return InstantArticle object.
     */
    private function __construct()
    {
        $this->header = Header::create();
        $this->addMetaProperty('op:generator', 'facebook-instant-articles-sdk-php');
        $this->addMetaProperty('op:generator:version', self::CURRENT_VERSION);
    }

    /**
     * Sets the canonical URL for the Instant Article. It is REQUIRED.
     *
     * @param string The canonical url of article. Ie: http://domain.com/article.html
     */
    public function withCanonicalURL($url)
    {
        Type::enforce($url, Type::STRING);
        $this->canonicalURL = $url;

        return $this;
    }

    /**
     * Sets the charset for the Instant Article. utf-8 by default.
     *
     * @param string The charset of article. Ie: "iso-8859-1"
     */
    public function withCharset($charset)
    {
        Type::enforce($charset, Type::STRING);
        $this->charset = $charset;

        return $this;
    }

    /**
     * Sets the style to be applied to this Instant Article
     *
     * @param string Name of the style
     */
    public function withStyle($style)
    {
        Type::enforce($style, Type::STRING);
        $this->style = $style;

        return $this;
    }

    /**
     * Use the strategy of auto ad placement
     */
    public function enableAutomaticAdPlacement()
    {
        $this->isAutomaticAdPlaced = true;
        return $this;
    }

    /**
     * Use the strategy of manual ad placement
     */
    public function disableAutomaticAdPlacement()
    {
        $this->isAutomaticAdPlaced = false;
        return $this;
    }

    /**
     * Sets the header content to this InstantArticle
     * @param Header to be added to this Article.
     */
    public function withHeader($header)
    {
        Type::enforce($header, Header::getClassName());
        $this->header = $header;

        return $this;
    }

    /**
     * Sets the footer content to this InstantArticle
     * @param Footer to be added to this Article.
     */
    public function withFooter($footer)
    {
        Type::enforce($footer, Footer::getClassName());
        $this->footer = $footer;

        return $this;
    }

    /**
     * Adds new child elements to this InstantArticle
     * @param Element to be added to this Article.
     */
    public function addChild($child)
    {
        Type::enforce(
            $child,
            array(
                Ad::getClassName(),
                Analytics::getClassName(),
                AnimatedGIF::getClassName(),
                Audio::getClassName(),
                Blockquote::getClassName(),
                Image::getClassName(),
                H1::getClassName(),
                H2::getClassName(),
                Interactive::getClassName(),
                ListElement::getClassName(),
                Map::getClassName(),
                Paragraph::getClassName(),
                Pullquote::getClassName(),
                RelatedArticles::getClassName(),
                Slideshow::getClassName(),
                SocialEmbed::getClassName(),
                Video::getClassName()
            )
        );
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return Header header element from the InstantArticle
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return Footer footer element from the InstantArticle
     */
    public function getFooter()
    {
        return $this->footer;
    }

    /**
     * @return array<Element> the elements this article contains
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Adds a meta property for the <head> of Instant Article.
     * @param string $property_name name of meta attribute
     * @param string $property_content content of meta attribute
     * @return $this for builder pattern
     */
    public function addMetaProperty($property_name, $property_content)
    {
        $this->metaProperties[$property_name] = $property_content;
        return $this;
    }

    public function render($doctype = '<!doctype html>', $format = false)
    {
        return parent::render($doctype, $format);
    }

    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        // Builds and appends head to the HTML document
        $html = $document->createElement('html');
        $head = $document->createElement('head');
        $html->appendChild($head);

        $link = $document->createElement('link');
        $link->setAttribute('rel', 'canonical');
        $link->setAttribute('href', $this->canonicalURL);
        $head->appendChild($link);

        $charset = $document->createElement('meta');
        $charset->setAttribute('charset', $this->charset);
        $head->appendChild($charset);

        $this->addMetaProperty('op:markup_version', $this->markupVersion);
        $this->addMetaProperty(
            'fb:use_automatic_ad_placement',
            $this->isAutomaticAdPlaced ? 'true' : 'false'
        );

        if ($this->style) {
            $this->addMetaProperty('fb:article_style', $this->style);
        }

        // Adds all meta properties
        foreach ($this->metaProperties as $property_name => $property_content) {
            $head->appendChild(
                $this->createMetaElement(
                    $document,
                    $property_name,
                    $property_content
                )
            );
        }

        // Build and append body and article tags to the HTML document
        $body = $document->createElement('body');
        $article = $document->createElement('article');
        $body->appendChild($article);
        $html->appendChild($body);
        if ($this->header) {
            $article->appendChild($this->header->toDOMElement($document));
        }
        if ($this->children) {
            foreach ($this->children as $child) {
                $article->appendChild($child->toDOMElement($document));
            }
            if ($this->footer) {
                $article->appendChild($this->footer->toDOMElement($document));
            }
        } else {
            $article->appendChild($document->createTextNode(''));
        }

        return $html;
    }

    private function createMetaElement($document, $property_name, $property_content)
    {
        $element = $document->createElement('meta');
        $element->setAttribute('property', $property_name);
        $element->setAttribute('content', $property_content);
        return $element;
    }
}

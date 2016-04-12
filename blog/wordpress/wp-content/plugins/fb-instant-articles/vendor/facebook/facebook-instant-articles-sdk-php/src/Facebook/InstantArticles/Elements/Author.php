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
 * Represents an author of the article.
 *
 * <addres>
 *    <a rel="facebook" href="http://facebook.com/everton.rosario">Everton</a>
 *    Everton Rosario is a passionate mountain biker on Facebook
 * </address>
 *
 * or
 *
 * <addres>
 *    <a href="http://twitter.com/evertonrosario">Everton On Twitter</a>
 *    Everton Rosario is a passionate mountain biker on Twitter
 * </address>
 *
 * or
 *
 * <addres>
 *    <a>Everton</a>
 *    Everton Rosario is a passionate mountain biker without Link
 * </address>
 */
class Author extends Element
{
    /**
     * @var string The author link
     */
    private $url;

    /**
     * @var string The author name
     */
    private $name;

    /**
     * @var string The author short description biography
     */
    private $description;

    /**
     * @var string Role or contribution of author
     */
    private $roleContribution;

    /**
     * Private constructor.
     * @see ArticleTime::create();.
     */
    private function __construct()
    {
    }

    /**
     * Creates an Author instance.
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Defines the link URL for the author
     * @param string the URL link for author. Ex: "http://facebook.com/everton.rosario"
     */
    public function withURL($url)
    {
        Type::enforce($url, Type::STRING);
        $this->url = $url;

        return $this;
    }

    /**
     * Author name.
     * @param string Author name. Ex: "Everton Rosario"
     */
    public function withName($name)
    {
        Type::enforce($name, Type::STRING);
        $this->name = $name;

        return $this;
    }

    /**
     * Author short description biography
     * @param string Describe the author biography.
     */
    public function withDescription($description)
    {
        Type::enforce($description, Type::STRING);
        $this->description = $description;

        return $this;
    }

    /**
     * Author role/contribution
     * @param string The author short text to caracterize role or contribution
     */
    public function withRoleContribution($role_contribution)
    {
        Type::enforce($role_contribution, Type::STRING);
        $this->roleContribution = $role_contribution;

        return $this;
    }

    /**
     * @param string author link url profile
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string author name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string author small introduction biography
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string author short text to define its contribution/role
     */
    public function getRoleContribution()
    {
        return $this->roleContribution;
    }

    /**
     * Structure and create the full Author in a XML format DOMElement.
     *
     * @param $document DOMDocument where this element will be appended. Optional
     */
    public function toDOMElement($document = null)
    {
        if (!$document) {
            $document = new \DOMDocument();
        }

        $author_url = $this->url ? $this->url : null;
        $is_fb_author = strpos($author_url, 'facebook.com') !== false;

        // Creates the root tag <address></address>
        $element = $document->createElement('address');

        // Creates the <a href...></> tag
        $ahref = $document->createElement('a');
        if ($author_url) {
            $ahref->setAttribute('href', $author_url);
        }
        if ($is_fb_author) {
            $ahref->setAttribute('rel', 'facebook');
        }
        if ($this->roleContribution) {
            $ahref->setAttribute('title', $this->roleContribution);
        }
        $ahref->appendChild($document->createTextNode($this->name));
        $element->appendChild($ahref);

        // Appends author description
        $element->appendChild($document->createTextNode($this->description));

        return $element;
    }
}

<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Rules;

use Facebook\InstantArticles\Transformer\Rules\AuthorRule;

class AuthorRuleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testCreateFromProperties()
    {
        $author_rule = AuthorRule::createFrom(
            array(
                "class" => "Facebook\\InstantArticles\\Transformer\\Rules\\AuthorRule",
                "selector" => "div.post-content > p > em",
                "properties" => array(
                    "author.url" => array(
                        "type" => "string",
                        "selector" => "a",
                        "attribute" => "href"
                    ),
                    "author.description" => array(
                        "type" => "string",
                        "selector" => "#text:nth-child(2)"
                    )
                )
            )
        );
        $this->assertEquals(get_class($author_rule), AuthorRule::getClassName());
    }

    public function testCreate()
    {
        $author_rule = AuthorRule::create()
            ->withSelector("div.post-content > p > em")
            ->withProperty(
                AuthorRule::PROPERTY_AUTHOR_URL,
                array(
                    "type" => "string",
                    "selector" => "a",
                    "attribute" => "href"
                )
            )
            ->withProperty(
                AuthorRule::PROPERTY_AUTHOR_NAME,
                array(
                    "type" => "string",
                    "selector" => "span"
                )
            );
        $this->assertEquals(get_class($author_rule), AuthorRule::getClassName());
    }
}

<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Rules;

use Facebook\InstantArticles\Transformer\Rules\PullquoteRule;
use Facebook\InstantArticles\Transformer\Rules\PullquoteCiteRule;
use Facebook\InstantArticles\Transformer\Transformer;
use Facebook\InstantArticles\Elements\InstantArticle;

class PullquoteRuleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \Logger::configure(
            array(
                'rootLogger' => array(
                    'appenders' => array('facebook-instantarticles-transformer')
                ),
                'appenders' => array(
                    'facebook-instantarticles-transformer' => array(
                        'class' => 'LoggerAppenderConsole',
                        'threshold' => 'INFO',
                        'layout' => array(
                            'class' => 'LoggerLayoutSimple'
                        )
                    )
                )
            )
        );
    }

    public function testTransformPullquote()
    {
        $transformer_rules =
            '{
                "rules" :
                    [
                        {
                            "class": "TextNodeRule"
                        },
                        {
                            "class": "ItalicRule",
                            "selector": "em"
                        },
                        {
                            "class": "ParagraphRule",
                            "selector": "p"
                        },
                        {
                            "class": "PassThroughRule",
                            "selector": "div.field-quote > p"
                        },
                        {
                            "class": "PassThroughRule",
                            "selector" : "div.field-quote"
                        },
                        {
                            "class" : "PullquoteRule",
                            "selector" : "blockquote.pull-quote"
                        },
                        {
                            "class" : "PullquoteCiteRule",
                            "selector" : "div.field-quote-author"
                        }
                    ]
            }';

        $html =
            '<blockquote class="pull-quote">'.
                '<div class="field-quote">'.
                    '<p>Here is a fancy pull quote for the <em>world</em> to see it all.</p>'.
                '</div>'.
                '<div class="field-quote-author">Matthew Oliveira</div>'.
            '</blockquote>';

        $expected =
            "<aside>Here is a fancy pull quote for the <i>world</i> to see it all.".
                "<cite>Matthew Oliveira</cite>".
            "</aside>\n";

        $instant_article = InstantArticle::create();
        $transformer = new Transformer();
        $transformer->loadRules($transformer_rules);

        $document = new \DOMDocument();
        $document->loadXML($html);

        $transformer->transform($instant_article, $document);

        $pullquote = $instant_article->getChildren()[0];
        $result = $pullquote->render('', true)."\n";

        $this->assertEquals($expected, $result);
    }

}

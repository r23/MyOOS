<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer;

use Facebook\InstantArticles\Elements\Element;
use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Elements\Header;
use Facebook\InstantArticles\Elements\Time;
use Facebook\InstantArticles\Elements\Author;
use Facebook\InstantArticles\Elements\Image;
use Facebook\InstantArticles\Elements\Caption;
use Facebook\InstantArticles\Elements\Paragraph;
use Facebook\InstantArticles\Elements\SlideShow;
use Facebook\InstantArticles\Elements\Analytics;
use Facebook\InstantArticles\Elements\Ad;
use Facebook\InstantArticles\Elements\Footer;
use Facebook\InstantArticles\Elements\Bold;

use Facebook\InstantArticles\Transformer\Rules\ParagraphRule;
use Facebook\InstantArticles\Transformer\Rules\TextNodeRule;
use Facebook\InstantArticles\Transformer\Rules\ItalicRule;
use Facebook\InstantArticles\Transformer\Rules\PassThroughRule;
use Facebook\InstantArticles\Transformer\Rules\BoldRule;
use Facebook\InstantArticles\Transformer\Rules\ImageRule;
use Facebook\InstantArticles\Transformer\Rules\AuthorRule;

use Symfony\Component\CssSelector\CssSelectorConverter;

class TransformerTest extends \PHPUnit_Framework_TestCase
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

    public function testSelfTransformerContent()
    {
        $json_file = file_get_contents("instant-article-example-rules.json", true);

        $instant_article = InstantArticle::create();
        $transformer = new Transformer();
        $transformer->loadRules($json_file);

        $html_file = file_get_contents("instant-article-example.html", true);

        libxml_use_internal_errors(true);
        $document = new \DOMDocument();
        $document->loadXML($html_file);
        libxml_use_internal_errors(false);

        $transformer->transform($instant_article, $document);
        $instant_article->addMetaProperty('op:generator:version', '1.0.0');
        $instant_article->addMetaProperty('op:transformer:version', '1.0.0');
        $warnings = $transformer->getWarnings();
        $result = $instant_article->render('', true)."\n";

        //var_dump($result);
        // print_r($warnings);
        $this->assertEquals($html_file, $result);
    }

    public function testTransformerAddAndGetRules()
    {
        $transformer = new Transformer();
        $rule1 = new ParagraphRule();
        $rule2 = new ItalicRule();
        $transformer->addRule($rule1);
        $transformer->addRule($rule2);
        $this->assertEquals(array($rule2, $rule1), $transformer->getRules());
    }

    public function testTransformerSetRules()
    {
        $transformer = new Transformer();
        $rule1 = new ParagraphRule();
        $rule2 = new ItalicRule();
        $transformer->setRules(array($rule1, $rule2));
        $this->assertEquals(array($rule1, $rule2), $transformer->getRules());
    }

    public function testTransformerResetRules()
    {
        $transformer = new Transformer();
        $rule1 = new ParagraphRule();
        $rule2 = new ItalicRule();
        $transformer->addRule($rule1);
        $transformer->addRule($rule2);
        $transformer->resetRules();
        $this->assertEquals(array(), $transformer->getRules());
    }
}

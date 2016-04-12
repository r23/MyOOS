<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Elements;

use Facebook\InstantArticles\Elements\H1;
use Facebook\InstantArticles\Elements\Bold;
use Facebook\InstantArticles\Elements\Italic;
use Facebook\InstantArticles\Elements\Anchor;

class H1Test extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testRenderBasic()
    {
        $h1 =
            H1::create()
                ->appendText('Title simple text.');

        $expected =
            '<h1>'.
                'Title simple text.'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithPosition()
    {
        $h1 =
            H1::create()
                ->appendText('Title simple text.')
                ->withPostion(Caption::POSITION_ABOVE);

        $expected =
            '<h1 class="op-vertical-above">'.
                'Title simple text.'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithTextAlign()
    {
        $h1 =
            H1::create()
                ->appendText('Title simple text.')
                ->withTextAlignment(Caption::ALIGN_LEFT);

        $expected =
            '<h1 class="op-left">'.
                'Title simple text.'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithPositionAndAlignment()
    {
        $h1 =
            H1::create()
                ->appendText('Title simple text.')
                ->withPostion(Caption::POSITION_ABOVE)
                ->withTextAlignment(Caption::ALIGN_LEFT);

        $expected =
            '<h1 class="op-vertical-above op-left">'.
                'Title simple text.'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithUnescapedHTML()
    {
        $h1 =
            H1::create()
                ->appendText(
                    '<b>Some</b> text to be <i>within</i> a <em>paragraph</em> for <strong>testing.</strong>'
                );

        $expected =
            '<h1>'.
                '&lt;b&gt;Some&lt;/b&gt; text to be &lt;i&gt;within&lt;/i&gt; a'.
                ' &lt;em&gt;paragraph&lt;/em&gt; for &lt;strong&gt;testing.&lt;/strong&gt;'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithFormattedText()
    {
        $h1 =
            H1::create()
                ->appendText(Bold::create()->appendText('Some'))
                ->appendText(' text to be ')
                ->appendText(Italic::create()->appendText('within'))
                ->appendText(' a ')
                ->appendText(Italic::create()->appendText('paragraph'))
                ->appendText(' for ')
                ->appendText(Bold::create()->appendText('testing.'));

        $expected =
            '<h1>'.
                '<b>Some</b> text to be <i>within</i> a <i>paragraph</i> for <b>testing.</b>'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithLink()
    {
        $h1 =
            H1::create()
                ->appendText('Some ')
                ->appendText(
                    Anchor::create()
                        ->withHRef('http://foo.com')
                        ->appendText('link')
                )
                ->appendText('.');

        $expected =
            '<h1>'.
                'Some <a href="http://foo.com">link</a>.'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithNestedFormattedText()
    {
        $h1 =
            H1::create()
                ->appendText(
                    Bold::create()
                        ->appendText('Some ')
                        ->appendText(Italic::create()->appendText('nested formatting'))
                        ->appendText('.')
                );

        $expected =
            '<h1>'.
                '<b>Some <i>nested formatting</i>.</b>'.
            '</h1>';

        $rendered = $h1->render();
        $this->assertEquals($expected, $rendered);
    }
}

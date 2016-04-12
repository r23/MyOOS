<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Elements;

use Facebook\InstantArticles\Elements\Pullquote;

class PullquoteTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testRenderBasic()
    {
        $analytics =
            Pullquote::create()
                ->appendText('Some text to be within an aside for testing.');

        $expected =
            '<aside>'.
                'Some text to be within an aside for testing.'.
            '</aside>';

        $rendered = $analytics->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithBoldStrongItalicEm()
    {
        $analytics =
            Pullquote::create()
                ->appendText(Bold::create()->appendText('Some'))
                ->appendText(' text to be ')
                ->appendText(Italic::create()->appendText('within'))
                ->appendText(' an ')
                ->appendText(Italic::create()->appendText('aside'))
                ->appendText(' for ')
                ->appendText(Bold::create()->appendText('testing.'));

        $expected =
            '<aside>'.
                '<b>Some</b> text to be <i>within</i> an <i>aside</i> for <b>testing.</b>'.
            '</aside>';

        $rendered = $analytics->render();
        $this->assertEquals($expected, $rendered);
    }

    public function testRenderWithAttribution()
    {
        $analytics =
            Pullquote::create()
                ->appendText(Bold::create()->appendText('Some'))
                ->appendText(' text to be ')
                ->appendText(Italic::create()->appendText('within'))
                ->appendText(' an ')
                ->appendText(Italic::create()->appendText('aside'))
                ->appendText(' for ')
                ->appendText(Bold::create()->appendText('testing.'))
                ->withAttribution('Some attribution');

        $expected =
            '<aside>'.
                '<b>Some</b> text to be <i>within</i> an <i>aside</i> for <b>testing.</b>'.
                '<cite>Some attribution</cite>'.
            '</aside>';

        $rendered = $analytics->render();
        $this->assertEquals($expected, $rendered);
    }
}

<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Client;

use Facebook\Facebook;
use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Elements\Paragraph;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    private $helper;
    private $facebook;

    public function setUp()
    {
        $this->facebook = $this->getMockBuilder('Facebook\Facebook')
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = new Helper(
            $this->facebook
        );
    }

    public function testGetPagesAndTokes()
    {
        $pagesAndTokens = array('page' => 'token');

        $accessToken =
            $this->getMockBuilder('Facebook\Authentication\AccessToken')
                ->disableOriginalConstructor()
                ->getMock();
        $accessToken
            ->expects($this->once())
            ->method('isLongLived')
            ->will($this->returnValue(true));
        $this->facebook
            ->expects($this->once())
            ->method('setDefaultAccessToken')
            ->with($accessToken);

        $response =
            $this->getMockBuilder('Facebook\FacebookResponse')
                ->disableOriginalConstructor()
                ->getMock();
        $response
            ->expects($this->once())
            ->method('getGraphEdge')
            ->will($this->returnValue($pagesAndTokens));

        $this->facebook
            ->expects($this->once())
            ->method('get')
            ->with('/me/accounts?fields=name,id,access_token,supports_instant_articles')
            ->will($this->returnValue($response));

        $pagesAndTokensReturned = $this->helper->getPagesAndTokens($accessToken);
        $this->assertEquals($pagesAndTokensReturned, $pagesAndTokens);
    }
}

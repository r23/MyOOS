<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Validators;

use Facebook\InstantArticles\Elements\Caption;
use Facebook\InstantArticles\Elements\Image;
use Facebook\InstantArticles\Elements\Video;
use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Elements\AnimatedGIF;
use Facebook\InstantArticles\Elements\Element;
use Facebook\InstantArticles\Validators\Type;

/**
 *
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{

    /*
        Type check tests ---------------
     */

    public function testIsType()
    {
        $result = Type::is(Caption::create(), array(Caption::getClassName()));
        $this->assertTrue($result);
    }

    public function testIsTypeWithArray()
    {
        $result = Type::is(array(1, 2, 3), Type::ARRAY_TYPE);
        $this->assertTrue($result);
    }

    public function testIsInSet()
    {
        $result = Type::is(
            Caption::create(),
            array(
                Caption::getClassName(),
                InstantArticle::getClassName(),
                Video::getClassName(),
                Image::getClassName()
            )
        );
        $this->assertTrue($result);
    }

    public function testIsNotIn()
    {
        $result = Type::is(
            Caption::create(),
            array(
                Image::getClassName()
            )
        );
        $this->assertFalse($result);
    }

    public function testIsNotInException()
    {
        try {
            $result = Type::enforce(
                Caption::create(),
                array(
                    Image::getClassName()
                )
            );
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            // success
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testIsNotInEmpty()
    {
        $result = Type::is(
            Caption::create(),
            array()
        );
        $this->assertFalse($result);
    }

    public function testIsNotInEmptyException()
    {
        try {
            $result = Type::enforce(
                Caption::create(),
                array()
            );
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            // success
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testIsNotInSet()
    {
        $result = Type::is(
            Caption::create(),
            array(
                InstantArticle::getClassName(),
                Video::getClassName(),
                Image::getClassName()
            )
        );
        $this->assertFalse($result);
    }

    public function testIsNotInSetException()
    {
        try {
            $result = Type::enforce(
                Caption::create(),
                array(
                    InstantArticle::getClassName(),
                    Video::getClassName(),
                    Image::getClassName()
                )
            );
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            // success
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testIsInInheritance()
    {
        $result = Type::is(
            AnimatedGIF::create(),
            array(
                Image::getClassName()
            )
        );
        $this->assertTrue($result);
    }

    public function testIsNotInInheritance()
    {
        $result = Type::is(
            AnimatedGIF::create(),
            array(
                Video::getClassName()
            )
        );
        $this->assertFalse($result);
    }

    public function testIsNotInInheritanceException()
    {
        try {
            $result = Type::enforce(
                AnimatedGIF::create(),
                array(
                    Video::getClassName()
                )
            );
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            // success
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testIsString()
    {
        $result = Type::is('test', Type::STRING);
        $this->assertTrue($result);
    }

    public function testIsNotString()
    {
        $result = Type::is(1, Type::STRING);
        $this->assertFalse($result);
    }

    public function testIsNotStringException()
    {
        try {
            $result = Type::enforce(1, Type::STRING);
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            // success
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testIsArrayOfString()
    {
        $result = Type::isArrayOf(array('1', '2'), Type::STRING);
        $this->assertTrue($result);
    }

    public function testIsArrayOfObject()
    {
        $result =
            Type::isArrayOf(
                array(Image::create(), Image::create()),
                Image::getClassName()
            );
        $this->assertTrue($result);
    }

    public function testIsArrayOfObjects()
    {
        $result =
            Type::isArrayOf(
                array(Image::create(), Video::create()),
                array(Image::getClassName(), Video::getClassName())
            );
        $this->assertTrue($result);
    }

    public function testIsArrayInInheritance()
    {
        $result = Type::isArrayOf(
            array(Image::create(), AnimatedGIF::create()),
            Image::getClassName()
        );
        $this->assertTrue($result);
    }

    public function testIsNotArrayInInheritance()
    {
        $result =
            Type::isArrayOf(
                array(Image::create(), Video::create()),
                Image::getClassName()
            );
        $this->assertFalse($result);
    }

    public function testIsNotArrayInInheritanceException()
    {
        try {
            $result =
                Type::enforceArrayOf(
                    array(Image::create(), Video::create()),
                    Image::getClassName()
                );
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            // success
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    /*
        Array size tests ---------------
     */
    public function testArraySize()
    {
        $result = Type::isArraySize(array(1,2,3), 3);
        $this->assertTrue($result);
    }

    public function testArrayNotSize()
    {
        $result = Type::isArraySize(array(1,2,3), 2);
        $this->assertFalse($result);
    }

    public function testArrayMinSizeExact()
    {
        $result = Type::isArraySizeGreaterThan(array(1,2,3), 3);
        $this->assertTrue($result);
    }

    public function testArrayMinSizeMore()
    {
        $result = Type::isArraySizeGreaterThan(array(1,2,3), 2);
        $this->assertTrue($result);
    }

    public function testArrayMinSizeFew()
    {
        $result = Type::isArraySizeGreaterThan(array(1,2,3), 4);
        $this->assertFalse($result);
    }

    public function testEnforceArrayMinSizeException()
    {
        try {
            $result = Type::enforceArraySizeGreaterThan(array(1,2,3), 4);
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testArrayMaxSizeExact()
    {
        $result = Type::isArraySizeLowerThan(array(1,2,3), 3);
        $this->assertTrue($result);
    }

    public function testArrayMaxSizeFew()
    {
        $result = Type::isArraySizeLowerThan(array(1,2,3), 4);
        $this->assertTrue($result);
    }

    public function testArrayMaxSizeMore()
    {
        $result = Type::isArraySizeLowerThan(array(1,2,3), 2);
        $this->assertFalse($result);
    }

    public function testEnforceArrayMaxSizeException()
    {
        try {
            $result = Type::enforceArraySizeLowerThan(array(1,2,3), 2);
            $this->fail('Should throw exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }

    public function testIsWithinTrueString()
    {
        $result = Type::isWithin('x', array('x', 'y', 'z'));
        $this->assertTrue($result);
    }

    public function testIsWithinTrueObj()
    {
        $image = Image::create();
        $video = Video::create();
        $result = Type::isWithin($image, array($image, $video, 'z'));
        $this->assertTrue($result);
    }

    public function testIsWithinFalse()
    {
        $result = Type::isWithin('a', array('x', 'y', 'z'));
        $this->assertFalse($result);
    }

    public function testIsWithinFalseObj()
    {
        $image = Image::create();
        $video = Video::create();
        $anotherImg = Image::create();
        $result = Type::isWithin($image, array($anotherImg, $video, 'z'));
        $this->assertFalse($result);
    }

    public function testEnforceWithinTrueString()
    {
        $result = Type::enforceWithin('x', array('x', 'y', 'z'));
        $this->assertTrue($result);
    }

    public function testEnforceWithinExceptionString()
    {
        try {
            $result = Type::enforceWithin('a', array('x', 'y', 'z'));
            $this->fail('Should trhow exception');
        } catch (\Exception $e) {
            $this->assertInstanceOf('InvalidArgumentException', $e);
        }
    }
}

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
 * Abstract class Audible
 * This class represents all elements that can contain Audio
 * <ul>
 *     <li>@see Image</li>
 *     <li>@see SlideShow</li>
 * </ul>.
 *
 * Example:
 *  <audio>
 *      <source src="http://mydomain.com/path/to/audio.mp3" />
 *  </audio>
 *
 * @see {link:https://developers.intern.facebook.com/docs/instant-articles/reference/image}
 */
abstract class Audible extends Element
{
    /**
     * Adds audio to this image.
     *
     * @param Audio The audio object
     */
    abstract public function withAudio($audio);
}

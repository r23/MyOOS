<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Getters;

use Facebook\InstantArticles\Transformer\Validators\Type;

class GetterFactory
{
    const TYPE_STRING_GETTER = 'string';
    const TYPE_INTEGER_GETTER = 'int';
    const TYPE_CHILDREN_GETTER = 'children';
    const TYPE_ELEMENT_GETTER = 'element';
    const TYPE_NEXTSIBLING_GETTER = 'sibling';
    const TYPE_EXISTS_GETTER = 'exists';
    const TYPE_XPATH_GETTER = 'xpath';

    /**
     * Creates an Getter class.
     *
     *  array(
     *        type => 'string' | 'children',
     *        selector => 'img.cover',
     *        [attribute] => 'src'
     *    )
     * @see StringGetter
     * @see ChildrenGetter
     * @see IntegerGetter
     * @see ElementGetter
     * @see NextSiblingGetter
     * @see ExistsGetter
     * @see XpathGetter
     * @param array<string, string> $getter_configuration that maps the properties for getter
     * @throws InvalidArgumentException if the type is invalid
     */
    public static function create($getter_configuration)
    {
        $GETTERS = array(
            self::TYPE_STRING_GETTER => StringGetter::getClassName(),
            self::TYPE_INTEGER_GETTER => IntegerGetter::getClassName(),
            self::TYPE_CHILDREN_GETTER => ChildrenGetter::getClassName(),
            self::TYPE_ELEMENT_GETTER => ElementGetter::getClassName(),
            self::TYPE_NEXTSIBLING_GETTER => NextSiblingGetter::getClassName(),
            self::TYPE_EXISTS_GETTER => ExistsGetter::getClassName(),
            self::TYPE_XPATH_GETTER => XpathGetter::getClassName()
        );

        $clazz = $getter_configuration['type'];
        if (array_key_exists($clazz, $GETTERS)) {
            $clazz = $GETTERS[$clazz];
        }
        $instance = new $clazz();
        $instance->createFrom($getter_configuration);
        return $instance;

        throw new \InvalidArgumentException(
            'Type not informed or unrecognized. The configuration must have'.
            ' a type of "StringGetter" or "ChildrenGetter"'
        );
    }
}

<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer;

use Facebook\InstantArticles\Transformer\Warnings\UnrecognizedElement;
use Facebook\InstantArticles\Transformer\Rules\Rule;
use Facebook\InstantArticles\Elements\InstantArticle;
use Facebook\InstantArticles\Validators\Type;

class Transformer
{
    private $rules = array();
    private $warnings = array();

    public $suppress_warnings = false;

    public function getWarnings()
    {
        return $this->warnings;
    }

    public function addRule($rule)
    {
        Type::enforce($rule, Rule::getClassName());
        // Adds in reversed order for bottom-top processing rules
        array_unshift($this->rules, $rule);
    }

    public function addWarning($warning)
    {
        $this->warnings[] = $warning;
    }

    public function transform($context, $node)
    {
        if (Type::is($context, InstantArticle::getClassName())) {
            $context->addMetaProperty('op:transformer', 'facebook-instant-articles-sdk-php');
            $context->addMetaProperty('op:transformer:version', InstantArticle::CURRENT_VERSION);
        }

        $log = \Logger::getLogger('facebook-instantarticles-transformer');
        if (!$node) {
            $e = new \Exception();
            $log->error(
                'Transformer::transform($context, $node) requires $node'.
                ' to be a valid one. Check on the stacktrace if this is '.
                'some nested transform operation and fix the selector.',
                $e->getTraceAsString()
            );
        }
        $current_context = $context;
        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                $matched = false;
                $log->debug("===========================");
                foreach ($this->rules as $rule) {
                    if ($rule->matches($context, $child)) {
                        $current_context = $rule->apply($this, $current_context, $child);
                        $matched = true;
                        break;
                    }
                }
                if (!$matched &&
                    !($child->nodeName === '#text' && trim($child->textContent) === '') &&
                    !($child->nodeName === '#comment') &&
                    !$this->suppress_warnings
                    ) {
                    $tag_content = $child->ownerDocument->saveXML($child);
                    $tag_trimmed = trim($tag_content);
                    if (!empty($tag_trimmed)) {
                        $log->debug('context class: '.get_class($context));
                        $log->debug('node name: '.$child->nodeName);
                        $log->debug("CONTENT NOT MATCHED: \n".$tag_content);
                    } else {
                        $log->debug('empty content ignored');
                    }

                    $this->addWarning(new UnrecognizedElement($current_context, $child));
                }
            }
        }
        return $context;
    }

    public function loadRules($json_file)
    {
        $configuration = json_decode($json_file, true);
        if ($configuration && isset($configuration['rules'])) {
            foreach ($configuration['rules'] as $configuration_rule) {
                $clazz = $configuration_rule['class'];
                try {
                    $factory_method = new \ReflectionMethod($clazz, 'createFrom');
                } catch (\ReflectionException $e) {
                    $factory_method =
                        new \ReflectionMethod(
                            'Facebook\\InstantArticles\\Transformer\\Rules\\'.$clazz,
                            'createFrom'
                        );
                }
                $this->addRule($factory_method->invoke(null, $configuration_rule));
            }
        }
    }

    /**
     * Removes all rules already set in this transformer instance.
     */
    public function resetRules()
    {
        $this->rules = array();
    }

    /**
     * Gets all rules already set in this transformer instance.
     *
     * @return array List of configured rules.
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Overrides all rules already set in this transformer instance.
     *
     * @return array List of configured rules.
     */
    public function setRules($rules)
    {
        Type::enforceArrayOf($rules, Rule::getClassName());
        $this->rules = $rules;
    }
}

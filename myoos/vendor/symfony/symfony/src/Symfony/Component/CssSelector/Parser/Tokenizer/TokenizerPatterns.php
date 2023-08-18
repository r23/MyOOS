<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\CssSelector\Parser\Tokenizer;

/**
 * CSS selector tokenizer patterns builder.
 *
 * This component is a port of the Python cssselect library,
 * which is copyright Ian Bicking, @see https://github.com/SimonSapin/cssselect.
 *
 * @author Jean-François Simon <jeanfrancois.simon@sensiolabs.com>
 *
 * @internal
 */
class TokenizerPatterns
{
    private $unicodeEscapePattern = '\\\\([0-9a-f]{1,6})(?:\r\n|[ \n\r\t\f])?';
    private $simpleEscapePattern = '\\\\(.)';
    private $newLineEscapePattern = '\\\\(?:\n|\r\n|\r|\f)';
    private $escapePattern;
    private $stringEscapePattern;
    private $nonAsciiPattern = '[^\x00-\x7F]';
    private $nmCharPattern;
    private $nmStartPattern;
    private $identifierPattern;
    private $hashPattern;
    private $numberPattern = '[+-]?(?:[0-9]*\.[0-9]+|[0-9]+)';
    private $quotedStringPattern;

    public function __construct()
    {
        $this->escapePattern = $this->unicodeEscapePattern.'|\\\\[^\n\r\f0-9a-f]';
        $this->stringEscapePattern = $this->newLineEscapePattern.'|'.$this->escapePattern;
        $this->nmCharPattern = '[_a-z0-9-]|'.$this->escapePattern.'|'.$this->nonAsciiPattern;
        $this->nmStartPattern = '[_a-z]|'.$this->escapePattern.'|'.$this->nonAsciiPattern;
        $this->identifierPattern = '-?(?:'.$this->nmStartPattern.')(?:'.$this->nmCharPattern.')*';
        $this->hashPattern = '#((?:'.$this->nmCharPattern.')+)';
        $this->quotedStringPattern = '([^\n\r\f\\\\%s]|'.$this->stringEscapePattern.')*';
    }

    public function getNewLineEscapePattern(): string
    {
        return '~'.$this->newLineEscapePattern.'~';
    }

    public function getSimpleEscapePattern(): string
    {
        return '~'.$this->simpleEscapePattern.'~';
    }

    public function getUnicodeEscapePattern(): string
    {
        return '~'.$this->unicodeEscapePattern.'~i';
    }

    public function getIdentifierPattern(): string
    {
        return '~^'.$this->identifierPattern.'~i';
    }

    public function getHashPattern(): string
    {
        return '~^'.$this->hashPattern.'~i';
    }

    public function getNumberPattern(): string
    {
        return '~^'.$this->numberPattern.'~';
    }

    public function getQuotedStringPattern(string $quote): string
    {
        return '~^'.sprintf($this->quotedStringPattern, $quote).'~i';
    }
}

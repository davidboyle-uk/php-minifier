<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents the start of a @media at-rule block.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtMediaStartToken extends CssAtBlockStartTokenAbstract
{
    /**
     * Sets the properties of the @media at-rule.
     *
     * @param array $mediaTypes media types
     * @return void
     */
    public function __construct(array $mediaTypes = array())
    {
        $this->mediaTypes = $mediaTypes;
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "@media " . implode(",", $this->mediaTypes) . "{";
    }
}

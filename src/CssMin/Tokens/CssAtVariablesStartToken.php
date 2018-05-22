<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents the start of a @variables at-rule block.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtVariablesStartToken extends CssAtBlockStartTokenAbstract
{
    /**
     * media types of the @variables at-rule block.
     *
     * @var array
     */
    public $mediaTypes = array();

    /**
     * Set the properties of a @variables at-rule token.
     *
     * @param array $mediaTypes media types
     * @return void
     */
    public function __construct($mediaTypes = null)
    {
        $this->mediaTypes = $mediaTypes ? $mediaTypes : array("all");
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "";
    }
}

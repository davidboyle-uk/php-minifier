<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents a @import at-rule.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtImportToken extends CssTokenAbstract
{
    /**
     * import path of the @import at-rule.
     *
     * @var string
     */
    public $import = "";

    /**
     * Media types of the @import at-rule.
     *
     * @var array
     */
    public $mediaTypes = array();

    /**
     * Set the properties of a @import at-rule token.
     *
     * @param string $import import path
     * @param array $mediaTypes Media types
     * @return void
     */
    public function __construct($import, $mediaTypes)
    {
        $this->import       = $import;
        $this->mediaTypes   = $mediaTypes ? $mediaTypes : array();
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "@import \""
            . $this->import
            . "\""
            . (count($this->mediaTypes) > 0 ? " "
            . implode(",", $this->mediaTypes) : "")
            . ";";
    }
}

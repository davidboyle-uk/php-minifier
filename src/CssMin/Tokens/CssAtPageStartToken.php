<?php

namespace PHPMin\CssMin\Tokens;

/**
* This {@link CssTokenAbstract CSS token} represents the start of a @page at-rule block.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtPageStartToken extends CssAtBlockStartTokenAbstract
{
    /**
     * selector.
     *
     * @var string
     */
    public $selector = "";

    /**
     * Sets the properties of the @page at-rule.
     *
     * @param string $selector selector
     * @return void
     */
    public function __construct($selector = "")
    {
        $this->selector = $selector;
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "@page" . ($this->selector ? " " . $this->selector : "") . "{";
    }
}

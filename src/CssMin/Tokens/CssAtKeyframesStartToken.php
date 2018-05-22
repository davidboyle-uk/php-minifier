<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents the start of a @keyframes at-rule block.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtKeyframesStartToken extends CssAtBlockStartTokenAbstract
{
    /**
     * Name of the at-rule.
     *
     * @var string
     */
    public $atRuleName = "keyframes";

    /**
     * Name
     *
     * @var string
     */
    public $name = "";

    /**
     * Sets the properties of the @page at-rule.
     *
     * @param string $selector selector
     * @return void
     */
    public function __construct($name, $atRuleName = null)
    {
        $this->name = $name;

        if (!is_null($atRuleName)) {
            $this->atRuleName = $atRuleName;
        }
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return "@" . $this->atRuleName . " \"" . $this->name . "\"{";
    }
}

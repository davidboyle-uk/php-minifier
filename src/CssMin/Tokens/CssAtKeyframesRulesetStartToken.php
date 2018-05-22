<?php

namespace PHPMin\CssMin\Tokens;

/**
 * This {@link CssTokenAbstract CSS token} represents the start of a ruleset of a @keyframes at-rule block.
 *
 * @package     CssMin/Tokens
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssAtKeyframesRulesetStartToken extends CssRulesetStartTokenAbstract
{
    /**
     * Array of selectors.
     *
     * @var array
     */
    public $selectors = array();

    /**
     * Set the properties of a ruleset token.
     *
     * @param array $selectors Selectors of the ruleset
     * @return void
     */
    public function __construct(array $selectors = array())
    {
        $this->selectors = $selectors;
    }

    /**
     * Implements {@link CssTokenAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        return implode(",", $this->selectors) . "{";
    }
}

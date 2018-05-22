<?php

namespace PHPMin\CssMin\Plugins\Parsers;

/**
 * {@link CssParserPluginAbstract Parser plugin} for preserve parsing expression() declaration values.
 *
 * This plugin return no {@link CssTokenAbstract CssToken}
 * but ensures that expression() declaration values will get parsed
 * properly.
 *
 * @package     CssMin/Parser/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssExpressionParserPlugin extends CssParserPluginAbstract
{
    /**
     * Count of left braces.
     *
     * @var integer
     */
    protected $leftBraces = 0;

    /**
     * Count of right braces.
     *
     * @var integer
     */
    protected $rightBraces = 0;

    /**
     * Implements {@link CssParserPluginAbstract::getTriggerChars()}.
     *
     * @return array
     */
    public function getTriggerChars()
    {
        return array("(", ")", ";", "}");
    }

    /**
     * Implements {@link CssParserPluginAbstract::getTriggerStates()}.
     *
     * @return array
     */
    public function getTriggerStates()
    {
        return false;
    }

    /**
     * Implements {@link CssParserPluginAbstract::parse()}.
     *
     * @param integer $index Current index
     * @param string $char Current char
     * @param string $previousChar Previous char
     * @return mixed TRUE will break the processing;
     * FALSE continue with the next plugin; integer set a new index and break the processing
     */
    public function parse($index, $char, $previousChar, $state)
    {
        // Start of expression
        if ($char === "("
            && strtolower(
                substr($this->parser->getSource(), $index - 10, 11)
            ) === "expression(" && $state !== "T_EXPRESSION"
        ) {
            $this->parser->pushState("T_EXPRESSION");
            $this->leftBraces++;
        } // Count left braces
        elseif ($char === "(" && $state === "T_EXPRESSION") {
            $this->leftBraces++;
        } // Count right braces
        elseif ($char === ")" && $state === "T_EXPRESSION") {
            $this->rightBraces++;
        } // Possible end of expression; if left and right braces are equal the expressen ends
        elseif (($char === ";" || $char === "}")
            && $state === "T_EXPRESSION"
            && $this->leftBraces === $this->rightBraces
        ) {
            $this->leftBraces = $this->rightBraces = 0;
            $this->parser->popState();
            return $index - 1;
        } else {
            return false;
        }

        return true;
    }
}

<?php

namespace PHPMin\CssMin\Plugins\Parsers;

use PHPMin\CssMin;
use PHPMin\CssMin\CssError;

/**
 * {@link CssParserPluginAbstract Parser plugin} for preserve parsing url() values.
 *
 * This plugin return no {@link CssTokenAbstract CssToken} but ensures that url() values will get parsed properly.
 *
 * @package     CssMin/Parser/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssUrlParserPlugin extends CssParserPluginAbstract
{
    /**
     * Implements {@link CssParserPluginAbstract::getTriggerChars()}.
     *
     * @return array
     */
    public function getTriggerChars()
    {
        return array("(", ")");
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
        // Start of string
        if ($char === "("
            && strtolower(substr($this->parser->getSource(), $index - 3, 4)) === "url("
            && $state !== "T_URL"
        ) {
            $this->parser->pushState("T_URL");
            $this->parser->setExclusive(__CLASS__);
        } // Escaped LF in url => remove escape backslash and LF
        elseif ($char === "\n" && $previousChar === "\\" && $state === "T_URL") {
            $this->parser->setBuffer(substr($this->parser->getBuffer(), 0, -2));
        } // Parse error: Unescaped LF in string literal
        elseif ($char === "\n" && $previousChar !== "\\" && $state === "T_URL") {
            $line = $this->parser->getBuffer();
            // Replace the LF with the url string delimiter
            $this->parser->setBuffer(substr($this->parser->getBuffer(), 0, -1) . ")");
            $this->parser->popState();
            $this->parser->unsetExclusive();
            CssMin::triggerError(
                new CssError(
                    __FILE__,
                    __LINE__,
                    __METHOD__
                    . ": Unterminated string literal",
                    $line
                )
            );
        } // End of string
        elseif ($char === ")" && $state === "T_URL") {
            $this->parser->popState();
            $this->parser->unsetExclusive();
        } else {
            return false;
        }

        return true;
    }
}

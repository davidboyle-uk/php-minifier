<?php

namespace PHPMin\CssMin\Plugins\Parsers;

use PHPMin\CssMin;
use PHPMin\CssMin\CssError;

/**
 * {@link CssParserPluginAbstract Parser plugin} for preserve parsing string values.
 *
 * This plugin return no {@link CssTokenAbstract CssToken} but ensures that string values will get parsed properly.
 *
 * @package     CssMin/Parser/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssStringParserPlugin extends CssParserPluginAbstract
{
    /**
     * Current string delimiter char.
     *
     * @var string
     */
    protected $delimiterChar = null;

    /**
     * Implements {@link CssParserPluginAbstract::getTriggerChars()}.
     *
     * @return array
     */
    public function getTriggerChars()
    {
        return array("\"", "'", "\n");
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
        if (($char === "\"" || $char === "'") && $state !== "T_STRING") {
            $this->delimiterChar = $char;
            $this->parser->pushState("T_STRING");
            $this->parser->setExclusive(__CLASS__);
        } // Escaped LF in string => remove escape backslash and LF
        elseif ($char === "\n" && $previousChar === "\\" && $state === "T_STRING") {
            $this->parser->setBuffer(substr($this->parser->getBuffer(), 0, -2));
        } // Parse error: Unescaped LF in string literal
        elseif ($char === "\n" && $previousChar !== "\\" && $state === "T_STRING") {
            $line = $this->parser->getBuffer();
            $this->parser->popState();
            $this->parser->unsetExclusive();
            $this->parser->setBuffer(
                substr(
                    $this->parser->getBuffer(),
                    0,
                    -1
                )
                . $this->delimiterChar
            );
            $this->delimiterChar = null;
        } // End of string
        elseif ($char === $this->delimiterChar && $state === "T_STRING") {
            // If the Previous char is a escape char count the amount of the previous escape chars. If the amount of
            // escape chars is uneven do not end the string
            if ($previousChar == "\\") {
                $source = $this->parser->getSource();
                $c      = 1;
                $i      = $index - 2;

                while (substr($source, $i, 1) === "\\") {
                    $c++;
                    $i--;
                }
                if ($c % 2) {
                    return false;
                }
            }
            $this->parser->popState();
            $this->parser->unsetExclusive();
            $this->delimiterChar = null;
        } else {
            return false;
        }
        
        return true;
    }
}

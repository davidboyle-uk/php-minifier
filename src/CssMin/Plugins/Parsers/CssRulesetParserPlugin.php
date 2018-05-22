<?php

namespace PHPMin\CssMin\Plugins\Parsers;

use PHPMin\CssMin;
use PHPMin\CssMin\CssError;
use PHPMin\CssMin\Tokens\CssRulesetDeclarationToken;
use PHPMin\CssMin\Tokens\CssRulesetStartToken;
use PHPMin\CssMin\Tokens\CssRulesetEndToken;

/**
 * {@link CssParserPluginAbstract Parser plugin} for parsing ruleset block with including declarations.
 *
 * Found rulesets will add a {@link CssRulesetStartToken} and {@link CssRulesetEndToken} to the
 * parser; including declarations as {@link CssRulesetDeclarationToken}.
 *
 * @package     CssMin/Parser/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssRulesetParserPlugin extends CssParserPluginAbstract
{
    /**
     * Implements {@link CssParserPluginAbstract::getTriggerChars()}.
     *
     * @return array
     */
    public function getTriggerChars()
    {
        return array(",", "{", "}", ":", ";");
    }

    /**
     * Implements {@link CssParserPluginAbstract::getTriggerStates()}.
     *
     * @return array
     */
    public function getTriggerStates()
    {
        return array("T_DOCUMENT", "T_AT_MEDIA", "T_RULESET::SELECTORS", "T_RULESET", "T_RULESET_DECLARATION");
    }

    /**
     * Selectors.
     *
     * @var array
     */
    protected $selectors = array();

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
        // Start of Ruleset and selectors
        if ($char === ","
            && ($state === "T_DOCUMENT" || $state === "T_AT_MEDIA" || $state === "T_RULESET::SELECTORS")
        ) {
            if ($state !== "T_RULESET::SELECTORS") {
                $this->parser->pushState("T_RULESET::SELECTORS");
            }
            $this->selectors[] = $this->parser->getAndClearBuffer(",{");
        } // End of selectors and start of declarations
        elseif ($char === "{"
            && ($state === "T_DOCUMENT" || $state === "T_AT_MEDIA" || $state === "T_RULESET::SELECTORS")
        ) {
            if ($this->parser->getBuffer() !== "") {
                $this->selectors[] = $this->parser->getAndClearBuffer(",{");
                if ($state == "T_RULESET::SELECTORS") {
                    $this->parser->popState();
                }
                $this->parser->pushState("T_RULESET");
                $this->parser->appendToken(new CssRulesetStartToken($this->selectors));
                $this->selectors = array();
            }
        } // Start of declaration
        elseif ($char === ":" && $state === "T_RULESET") {
            $this->parser->pushState("T_RULESET_DECLARATION");
            $this->buffer = $this->parser->getAndClearBuffer(":;", true);
        } // Unterminated ruleset declaration
        elseif ($char === ":" && $state === "T_RULESET_DECLARATION") {
            // Ignore Internet Explorer filter declarations
            if ($this->buffer === "filter") {
                return false;
            }
        } // End of declaration
        elseif (($char === ";" || $char === "}") && $state === "T_RULESET_DECLARATION") {
            $value = $this->parser->getAndClearBuffer(";}");
            if (strtolower(substr($value, -10, 10)) === "!important") {
                $value = trim(substr($value, 0, -10));
                $isImportant = true;
            } else {
                $isImportant = false;
            }
            $this->parser->popState();
            $this->parser->appendToken(
                new CssRulesetDeclarationToken($this->buffer, $value, $this->parser->getMediaTypes(), $isImportant)
            );

            // Declaration ends with a right curly brace; so we have to end the ruleset
            if ($char === "}") {
                $this->parser->appendToken(new CssRulesetEndToken());
                $this->parser->popState();
            }
            $this->buffer = "";
        } // End of ruleset
        elseif ($char === "}" && $state === "T_RULESET") {
            $this->parser->popState();
            $this->parser->clearBuffer();
            $this->parser->appendToken(new CssRulesetEndToken());
            $this->buffer = "";
            $this->selectors = array();
        } else {
            return false;
        }

        return true;
    }
}

<?php

namespace PHPMin\CssMin\Plugins\Parsers;

use PHPMin\CssMin\Tokens\CssCommentToken;

/**
 * {@link CssParserPluginAbstract Parser plugin} for parsing comments.
 *
 * Adds a {@link CssCommentToken} to the parser if a comment was found.
 *
 * @package     CssMin/Parser/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssCommentParserPlugin extends CssParserPluginAbstract
{
    /**
     * Implements {@link CssParserPluginAbstract::getTriggerChars()}.
     *
     * @return array
     */
    public function getTriggerChars()
    {
        return array("*", "/");
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
     * Stored buffer for restore.
     *
     * @var string
     */
    protected $restoreBuffer = "";

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
        if ($char === "*" && $previousChar === "/" && $state !== "T_COMMENT") {
            $this->parser->pushState("T_COMMENT");
            $this->parser->setExclusive(__CLASS__);
            $this->restoreBuffer = substr($this->parser->getAndClearBuffer(), 0, -2);
        } elseif ($char === "/" && $previousChar === "*" && $state === "T_COMMENT") {
            $this->parser->popState();
            $this->parser->unsetExclusive();
            $this->parser->appendToken(new CssCommentToken("/*" . $this->parser->getAndClearBuffer()));
            $this->parser->setBuffer($this->restoreBuffer);
        } else {
            return false;
        }

        return true;
    }
}

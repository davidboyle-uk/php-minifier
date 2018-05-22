<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin\Tokens\CssTokenAbstract;

/**
 * This {@link CssMinifierPluginAbstract} will compress several unit values to their short notations. Examples:
 *
 * <code>
 * padding: 0.5em;
 * border: 0px;
 * margin: 0 0 0 0;
 * </code>
 *
 * Will get compressed to:
 *
 * <code>
 * padding:.5px;
 * border:0;
 * margin:0;
 * </code>
 *
 * --
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssCompressUnitValuesMinifierPlugin extends CssMinifierPluginAbstract
{
    /**
     * Regular expression used for matching and replacing unit values.
     *
     * @var array
     */
    protected $re = array(
        "/(^| |-)0\.([0-9]+?)(0+)?(%|em|ex|px|in|cm|mm|pt|pc)/iS" => "\${1}.\${2}\${4}",
        "/(^| )-?(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/iS" => "\${1}0",
        "/(^0\s0\s0\s0)|(^0\s0\s0$)|(^0\s0$)/iS" => "0"
    );

    /**
     * Regular expression matching the value.
     *
     * @var string
     */
    protected $reMatch = "/(^| |-)0\.([0-9]+?)(0+)?(%|em|ex|px|in|cm|mm|pt|pc)|(^| )-?"
                       . "(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)|(^0\s0\s0\s0$)|(^0\s0\s0$)|(^0\s0$)/iS";

    /**
     * Implements {@link CssMinifierPluginAbstract::minify()}.
     *
     * @param CssTokenAbstract $token token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    public function apply(CssTokenAbstract &$token)
    {
        if (preg_match($this->reMatch, $token->value)) {
            foreach ($this->re as $reMatch => $reReplace) {
                $token->value = preg_replace($reMatch, $reReplace, $token->value);
            }
            return true;
        }
        return false;
    }

    /**
     * Implements {@link aMinifierPlugin::getTriggerTokens()}
     *
     * @return array
     */
    public function getTriggerTokens()
    {
        return array(
            "PHPMin\CssMin\Tokens\CssAtFontFaceDeclarationToken",
            "PHPMin\CssMin\Tokens\CssAtPageDeclarationToken",
            "PHPMin\CssMin\Tokens\CssRulesetDeclarationToken"
        );
    }
}

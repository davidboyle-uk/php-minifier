<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin\Tokens\CssTokenAbstract;

/**
 * This {@link CssMinifierPluginAbstract} will convert hexadecimal color value with 6 chars to their 3 char hexadecimal
 * notation (if possible).
 *
 * Example:
 * <code>
 * color: #aabbcc;
 * </code>
 *
 * Will get converted to:
 * <code>
 * color:#abc;
 * </code>
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssCompressColorValuesMinifierPlugin extends CssMinifierPluginAbstract
{
    /**
     * Regular expression matching 6 char hexadecimal color values.
     *
     * @var string
     */
    protected $reMatch = "/\#([0-9a-f]{6})/iS";

    /**
     * Implements {@link CssMinifierPluginAbstract::minify()}.
     *
     * @param CssTokenAbstract $token Token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    public function apply(CssTokenAbstract &$token)
    {
        if (strpos($token->value, "#") !== false && preg_match($this->reMatch, $token->value, $m)) {
            $value = strtolower($m[1]);
            if ($value[0] == $value[1] && $value[2] == $value[3] && $value[4] == $value[5]) {
                $token->value = str_replace($m[0], "#" . $value[0] . $value[2] . $value[4], $token->value);
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

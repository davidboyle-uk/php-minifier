<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin\Tokens\CssTokenAbstract;

/**
 * This {@link CssMinifierPluginAbstract} will convert a color value in rgb notation to hexadecimal notation.
 *
 * Example:
 * <code>
 * color: rgb(200,60%,5);
 * </code>
 *
 * Will get converted to:
 * <code>
 * color:#c89905;
 * </code>
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssConvertRgbColorsMinifierPlugin extends CssMinifierPluginAbstract
{
    /**
     * Regular expression matching the value.
     *
     * @var string
     */
    protected $reMatch = "/rgb\s*\(\s*([0-9%]+)\s*,\s*([0-9%]+)\s*,\s*([0-9%]+)\s*\)/iS";

    /**
     * Implements {@link CssMinifierPluginAbstract::minify()}.
     *
     * @param CssTokenAbstract $token Token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    public function apply(CssTokenAbstract &$token)
    {
        if (stripos($token->value, "rgb") !== false && preg_match($this->reMatch, $token->value, $m)) {
            for ($i = 1, $l = count($m); $i < $l; $i++) {
                if (strpos($m[$i], "%") !== false) {
                    $m[$i] = substr($m[$i], 0, -1);
                    $m[$i] = (int) (256 * ($m[$i] / 100));
                }
                $m[$i] = str_pad(dechex($m[$i]), 2, "0", STR_PAD_LEFT);
            }
            $token->value = str_replace($m[0], "#" . $m[1] . $m[2] . $m[3], $token->value);
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

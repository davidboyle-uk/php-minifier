<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin\Tokens\CssTokenAbstract;
use PHPMin\JsMin;

/**
 * This {@link CssMinifierPluginAbstract} compress the content of expresssion() declaration values.
 *
 * For compression of expressions {@link https://github.com/rgrove/jsmin-php/ JSMin} will get used. JSMin have to be
 * already included or loadable via {@link http://goo.gl/JrW54 PHP autoloading}.
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssCompressExpressionValuesMinifierPlugin extends CssMinifierPluginAbstract
{
    /**
     * Implements {@link CssMinifierPluginAbstract::minify()}.
     *
     * @param CssTokenAbstract $token Token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    public function apply(CssTokenAbstract &$token)
    {
        if (class_exists("PHPMin\JsMin") && stripos($token->value, "expression(") !== false) {
            $value  = $token->value;
            $value  = substr($token->value, stripos($token->value, "expression(") + 10);
            $value  = trim(JsMin::minify($value));
            $token->value = "expression" . $value;
            
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

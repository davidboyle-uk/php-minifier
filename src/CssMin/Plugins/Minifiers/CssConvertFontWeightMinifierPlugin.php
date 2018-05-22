<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Tokens\CssTokenAbstract;

/**
 * This {@link CssMinifierPluginAbstract} will convert the font-weight values normal and bold to their numeric notation.
 *
 * Example:
 * <code>
 * font-weight: normal;
 * font: bold 11px monospace;
 * </code>
 *
 * Will get converted to:
 * <code>
 * font-weight:400;
 * font:700 11px monospace;
 * </code>
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssConvertFontWeightMinifierPlugin extends CssMinifierPluginAbstract
{
    /**
     * Array of included declaration properties this plugin will process; others declaration properties will get
     * ignored.
     *
     * @var array
     */
    protected $include = array(
        "font",
        "font-weight"
    );

    /**
     * Regular expression matching the value.
     *
     * @var string
     */
    protected $reMatch = null;

    /**
     * Regular expression replace the value.
     *
     * @var string
     */
    protected $reReplace = "\"\${1}\" . \$this->transformation[\"\${2}\"] . \"\${3}\"";

    /**
     * Transformation table used by the
     * {@link CssConvertFontWeightMinifierPlugin::$reReplace replace regular expression}.
     *
     * @var array
     */
    protected $transformation = array(
        "normal"    => "400",
        "bold"      => "700"
    );

    /**
     * Overwrites {@link CssMinifierPluginAbstract::__construct()}.
     *
     * The constructor will create the {@link CssConvertFontWeightMinifierPlugin::$reReplace replace regular expression}
     * based on the {@link CssConvertFontWeightMinifierPlugin::$transformation transformation table}.
     *
     * @param CssMinifier $minifier The CssMinifier object of this plugin.
     * @return void
     */
    public function __construct(CssMinifier $minifier)
    {
        $this->reMatch = "/(^|\s)+(" . implode("|", array_keys($this->transformation)). ")(\s|$)+/iS";
        parent::__construct($minifier);
    }

    /**
     * Implements {@link CssMinifierPluginAbstract::minify()}.
     *
     * @param CssTokenAbstract $token Token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    public function apply(CssTokenAbstract &$token)
    {
        if (in_array($token->property, $this->include) && preg_match($this->reMatch, $token->value, $m)) {
            $token->value = preg_replace_callback(
                $this->reMatch,
                function ($matches) {
                    return $matches[1] . $this->transformation[$matches[2]] . $matches[3];
                },
                $token->value
            );
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

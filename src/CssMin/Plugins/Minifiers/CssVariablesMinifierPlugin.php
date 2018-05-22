<?php

namespace PHPMin\CssMin\Plugins\Minifiers;

use PHPMin\CssMin;
use PHPMin\CssMin\CssError;
use PHPMin\CssMin\Tokens\CssTokenAbstract;
use PHPMin\CssMin\Tokens\CssNullToken;

/**
 * This {@link CssMinifierPluginAbstract}
 * will process var-statement and sets the declaration value to the variable value.
 *
 * This plugin only apply the variable values.
 * The variable values itself will get parsed by the
 * {@link CssVariablesMinifierFilter}.
 *
 * Example:
 * <code>
 * @variables
 *      {
 *      defaultColor: black;
 *      }
 * color: var(defaultColor);
 * </code>
 *
 * Will get converted to:
 * <code>
 * color:black;
 * </code>
 *
 * @package     CssMin/Minifier/Plugins
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssVariablesMinifierPlugin extends CssMinifierPluginAbstract
{
    /**
     * Regular expression matching a value.
     *
     * @var string
     */
    protected $reMatch = "/var\((.+)\)/iSU";

    /**
     * Parsed variables.
     *
     * @var array
     */
    protected $variables = null;

    /**
     * Returns the variables.
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * Implements {@link CssMinifierPluginAbstract::minify()}.
     *
     * @param CssTokenAbstract $token Token to process
     * @return boolean Return TRUE to break the processing of this token; FALSE to continue
     */
    public function apply(CssTokenAbstract &$token)
    {
        if (stripos($token->value, "var") !== false && preg_match_all($this->reMatch, $token->value, $m)) {
            $mediaTypes = $token->mediaTypes;

            if (!in_array("all", $mediaTypes)) {
                $mediaTypes[] = "all";
            }

            for ($i=0, $l=count($m[0]); $i<$l; $i++) {
                $variable = trim($m[1][$i]);

                foreach ($mediaTypes as $mediaType) {
                    if (isset($this->variables[$mediaType], $this->variables[$mediaType][$variable])) {
                        // Variable value found => set the declaration value to the variable value and return
                        $token->value = str_replace($m[0][$i], $this->variables[$mediaType][$variable], $token->value);
                        return true;
                    }
                }

                // If no value was found trigger an error and replace the token with a CssNullToken
                CssMin::triggerError(
                    new CssError(
                        __FILE__,
                        __LINE__,
                        __METHOD__
                        . ": No value found for variable <code>"
                        . $variable
                        . "</code> in media types <code>"
                        . implode(", ", $mediaTypes)
                        . "</code>",
                        (string) $token
                    )
                );
                $token = new CssNullToken();
                
                return true;
            }
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

    /**
     * Sets the variables.
     *
     * @param array $variables Variables to set
     * @return void
     */
    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }
}

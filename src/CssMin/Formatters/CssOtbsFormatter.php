<?php

namespace PHPMin\CssMin\Formatters;

/**
 * {@link CssFormatterAbstract Formatter} returning the CSS source in
 * {@link http://goo.gl/j4XdU OTBS indent style} (The One True Brace Style).
 *
 * @package     CssMin/Formatter
 * @author      2018 David Bolye <https://github.com/dbx123>
 * @author      2014 Joe Scylla <joe.scylla@gmail.com>
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 * @version     1.0.1
 */
class CssOtbsFormatter extends CssFormatterAbstract
{
    /**
     * Implements {@link CssFormatterAbstract::__toString()}.
     *
     * @return string
     */
    public function __toString()
    {
        $r              = array();
        $level          = 0;

        for ($i=0, $l=count($this->tokens); $i<$l; $i++) {
            $token      = $this->tokens[$i];
            $class      = get_class($token);
            $indent     = str_repeat($this->indent, $level);

            if ($class === "PHPMin\CssMin\Tokens\CssCommentToken") {
                $lines = array_map("trim", explode("\n", $token->comment));
                for ($ii = 0, $ll = count($lines); $ii < $ll; $ii++) {
                    $r[] = $indent . (substr($lines[$ii], 0, 1) == "*" ? " " : "") . $lines[$ii];
                }
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtCharsetToken") {
                $r[] = $indent . "@charset " . $token->charset . ";";
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtFontFaceStartToken") {
                $r[] = $indent . "@font-face {";
                $level++;
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtImportToken") {
                $r[] = $indent . "@import " . $token->import . " " . implode(", ", $token->mediaTypes) . ";";
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtKeyframesStartToken") {
                $r[] = $indent . "@keyframes \"" . $token->name . "\" {";
                $level++;
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtMediaStartToken") {
                $r[] = $indent . "@media " . implode(", ", $token->mediaTypes) . " {";
                $level++;
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtPageStartToken") {
                $r[] = $indent . "@page {";
                $level++;
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtVariablesStartToken") {
                $r[] = $indent . "@variables " . implode(", ", $token->mediaTypes) . " {";
                $level++;
            } elseif ($class === "PHPMin\CssMin\Tokens\CssRulesetStartToken" ||
                $class === "PHPMin\CssMin\Tokens\CssAtKeyframesRulesetStartToken") {
                $r[] = $indent . implode(", ", $token->selectors) . " {";
                $level++;
            } elseif ($class == "PHPMin\CssMin\Tokens\CssAtFontFaceDeclarationToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtKeyframesRulesetDeclarationToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtPageDeclarationToken"
                || $class == "PHPMin\CssMin\Tokens\CssAtVariablesDeclarationToken"
                || $class === "PHPMin\CssMin\Tokens\CssRulesetDeclarationToken"
            ) {
                $declaration = $indent . $token->property . ": ";
                if ($this->padding) {
                    $declaration = str_pad($declaration, $this->padding, " ", STR_PAD_RIGHT);
                }
                $r[] = $declaration . $token->value . ($token->isImportant ? " !important" : "") . ";";
            } elseif ($class === "PHPMin\CssMin\Tokens\CssAtFontFaceEndToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtMediaEndToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtKeyframesEndToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtKeyframesRulesetEndToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtPageEndToken"
                || $class === "PHPMin\CssMin\Tokens\CssAtVariablesEndToken"
                || $class === "PHPMin\CssMin\Tokens\CssRulesetEndToken"
            ) {
                $level--;
                $r[] = str_repeat($indent, $level) . "}";
            }
        }

        return implode("\n", $r);
    }
}

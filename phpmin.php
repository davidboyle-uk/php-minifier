<?php

namespace PHPMin;

require_once(__DIR__ . "/src/JsMin/JsMin.php");
require_once(__DIR__ . "/src/JsMin/Exceptions/JSMinUnterminatedCommentException.php");
require_once(__DIR__ . "/src/JsMin/Exceptions/JSMinUnterminatedRegExpException.php");
require_once(__DIR__ . "/src/JsMin/Exceptions/JSMinUnterminatedStringException.php");

require_once(__DIR__ . "/src/CssMin/Exceptions/CssMinException.php");
require_once(__DIR__ . "/src/CssMin/CssMin.php");
require_once(__DIR__ . "/src/CssMin/CssMin/CssError.php");
require_once(__DIR__ . "/src/CssMin/CssMin/CssMinifier.php");
require_once(__DIR__ . "/src/CssMin/CssMin/CssParser.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssMinifierFilterAbstract.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssConvertLevel3AtKeyframesMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssConvertLevel3PropertiesMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssImportImportsMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssRemoveCommentsMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssRemoveEmptyAtBlocksMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssRemoveEmptyRulesetsMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssRemoveLastDelarationSemiColonMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssSortRulesetPropertiesMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Filters/CssVariablesMinifierFilter.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssTokenAbstract.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtBlockEndTokenAbstract.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtBlockStartTokenAbstract.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssDeclarationTokenAbstract.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssRulesetEndTokenAbstract.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssRulesetStartTokenAbstract.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtCharsetToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtFontFaceDeclarationToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtFontFaceEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtFontFaceStartToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtImportToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtKeyframesEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtKeyframesRulesetDeclarationToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtKeyframesRulesetEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtKeyframesRulesetStartToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtKeyframesStartToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtMediaEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtMediaStartToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtPageDeclarationToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtPageEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtPageStartToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtVariablesDeclarationToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtVariablesEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssAtVariablesStartToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssCommentToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssNullToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssRulesetDeclarationToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssRulesetEndToken.php");
require_once(__DIR__ . "/src/CssMin/Tokens/CssRulesetStartToken.php");
require_once(__DIR__ . "/src/CssMin/Formatters/CssFormatterAbstract.php");
require_once(__DIR__ . "/src/CssMin/Formatters/CssOtbsFormatter.php");
require_once(__DIR__ . "/src/CssMin/Formatters/CssWhitesmithsFormatter.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssParserPluginAbstract.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtCharsetParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtFontFaceParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtImportParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtKeyframesParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtMediaParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtPageParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssAtVariablesParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssCommentParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssExpressionParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssRulesetParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssStringParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Parsers/CssUrlParserPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssMinifierPluginAbstract.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssVariablesMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssCompressColorValuesMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssCompressExpressionValuesMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssCompressUnitValuesMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssConvertFontWeightMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssConvertHslColorsMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssConvertNamedColorsMinifierPlugin.php");
require_once(__DIR__ . "/src/CssMin/Plugins/Minifiers/CssConvertRgbColorsMinifierPlugin.php");

require_once(__DIR__ . "/src/HtmlMin/HtmlMin.php");

use PHPMin\HtmlMin;
use PHPMin\CssMin;
use PHPMin\JsMin;

// Open-source (BSD) PHP inline minifier functions for HTML, XHTML, HTML5, CSS 1-3 and Javascript.
// BSD Licensed  - https://github.com/dbx123/php-minifier/blob/master/LICENSE
//
// Usage
//  $output = \PHPMin\Minify::css($any_css);
//  $output = \PHPMin\Minify::js($any_js);
//  $output = \PHPMin\Minify::html($any_html);

class Minify
{
    public static function css($css)
    {
        return CssMin::minify($css);
    }

    public static function js($js)
    {
        return JsMin::minify($js);
    }

    public static function html($html)
    {
        return HtmlMin::minify($html);
    }
}

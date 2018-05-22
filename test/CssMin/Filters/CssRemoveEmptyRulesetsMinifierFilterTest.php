<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Filters\CssRemoveEmptyRulesetsMinifierFilter;
use PHPMin\CssMin\Tokens\CssRulesetStartToken;
use PHPMin\CssMin\Tokens\CssRulesetEndToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetStartToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetEndToken;

/**
 * Tests for CssRemoveEmptyRulesetsMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssRemoveEmptyRulesetsMinifierFilterTest extends TestCase
{
    /* CssRemoveEmptyRulesetMinifierFilter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => false,
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => true,
        "RemoveEmptyAtBlocks"           => false,
        "ConvertLevel3Properties"       => false,
        "ConvertLevel3AtKeyframes"      => false,
        "Variables"                     => false,
        "RemoveLastDelarationSemiColon" => false
    ];

    /* Plugin Configuration */
    protected $plugins = [
        "Variables"                     => false,
        "ConvertFontWeight"             => false,
        "ConvertHslColors"              => false,
        "ConvertRgbColors"              => false,
        "ConvertNamedColors"            => false,
        "CompressColorValues"           => false,
        "CompressUnitValues"            => false,
        "CompressExpressionValues"      => false
    ];

    /* CssMin */
    protected $minifier;

    protected function setUp()
    {
        $this->minifier = new CssMin(
            null,
            $this->filters,
            $this->plugins
        );

        $this->sut = new CssRemoveEmptyRulesetsMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            []
        );
    }

    public function testCssRemoveEmptyRulesetsMinifierFilterRuleset()
    {
        $tokens = [
            new CssRulesetStartToken(),
            new CssRulesetEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssRemoveEmptyRulesetsMinifierFilterKeyframeRuleset()
    {
        $tokens = [
            new CssAtKeyframesRulesetStartToken(),
            new CssAtKeyframesRulesetEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }
}

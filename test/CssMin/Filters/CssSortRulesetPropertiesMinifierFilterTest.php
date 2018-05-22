<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Filters\CssSortRulesetPropertiesMinifierFilter;
use PHPMin\CssMin\Tokens\CssNullToken;
use PHPMin\CssMin\Tokens\CssRulesetStartToken;
use PHPMin\CssMin\Tokens\CssRulesetEndToken;
use PHPMin\CssMin\Tokens\CssRulesetDeclarationToken;

/**
 * Tests for CssSortRulesetPropertiesMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssSortRulesetPropertiesMinifierFilterTest extends TestCase
{
    /* CssSortRulesetPropertiesMinifierFilter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => false,
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => false,
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

        $this->sut = new CssSortRulesetPropertiesMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            []
        );
    }

    public function testCssSortRulesetPropertiesMinifierFilterRuleset()
    {
        $tokens = [
            new CssRulesetStartToken(),
            new CssRulesetEndToken(),
        ];
        $expected = 0;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssSortRulesetPropertiesMinifierFilterUnordered()
    {
        $tokens = [
            new CssNullToken(),
            new CssRulesetStartToken(),
            new CssNullToken(),
            new CssRulesetEndToken(),
        ];
        $expected = 0;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssSortRulesetPropertiesMinifierFilterRandom()
    {
        $tokens = [
            new CssRulesetStartToken(),
        ];
        $expected = 0;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssSortRulesetPropertiesMinifierFilterNullInternal()
    {
        $tokens = [
            new CssRulesetStartToken(),
            new CssNullToken(),
            new CssNullToken(),
            new CssRulesetEndToken(),
        ];
        $expected = 0;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssSortRulesetPropertiesMinifierFilterDeclarations()
    {
        $tokens = [
            new CssRulesetStartToken(),
            new CssRulesetDeclarationToken('padding', '1.25rem'),
            new CssRulesetDeclarationToken('margin', '5px'),
            new CssRulesetEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssSortRulesetPropertiesMinifierFilterDeclarationsSortRequired()
    {
        $tokens = [
            new CssRulesetStartToken(),
            new CssRulesetDeclarationToken('padding', '1.25rem'),
            new CssRulesetDeclarationToken('padding', '5px'),
            new CssRulesetEndToken(),
        ];
        $expected = 0;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssSortRulesetPropertiesMinifierFilterDeclarationsMultiSortRequired()
    {
        $tokens = [
            new CssRulesetStartToken(),
            new CssRulesetDeclarationToken('padding', '1.25rem'),
            new CssRulesetDeclarationToken('margin', '5px'),
            new CssRulesetDeclarationToken('border-left', '1.25rem'),
            new CssRulesetDeclarationToken('border-right', '5px'),
            new CssRulesetEndToken(),
        ];
        $expected = 4;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }
}

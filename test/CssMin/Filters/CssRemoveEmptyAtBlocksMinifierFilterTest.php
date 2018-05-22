<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Filters\CssRemoveEmptyAtBlocksMinifierFilter;
use PHPMin\CssMin\Tokens\CssAtFontFaceStartToken;
use PHPMin\CssMin\Tokens\CssAtFontFaceEndToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesStartToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesEndToken;
use PHPMin\CssMin\Tokens\CssAtPageStartToken;
use PHPMin\CssMin\Tokens\CssAtPageEndToken;
use PHPMin\CssMin\Tokens\CssAtMediaStartToken;
use PHPMin\CssMin\Tokens\CssAtMediaEndToken;

/**
 * Tests for CssRemoveEmptyAtBlocksMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssRemoveEmptyAtBlocksMinifierFilterTest extends TestCase
{
    /* CssRemoveEmptyAtBlocksMinifierFilter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => false,
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => false,
        "RemoveEmptyAtBlocks"           => true,
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

        $this->sut = new CssRemoveEmptyAtBlocksMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            []
        );
    }

    public function testCssRemoveEmptyAtBlocksMinifierFilterFontFace()
    {
        $tokens = [
            new CssAtFontFaceStartToken(),
            new CssAtFontFaceEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssRemoveEmptyAtBlocksMinifierFilterKeyframes()
    {
        $tokens = [
            new CssAtKeyframesStartToken('mymove', 'keyframes'),
            new CssAtKeyframesEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssRemoveEmptyAtBlocksMinifierFilterPage()
    {
        $tokens = [
            new CssAtPageStartToken(),
            new CssAtPageEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssRemoveEmptyAtBlocksMinifierFilterMedia()
    {
        $tokens = [
            new CssAtMediaStartToken(),
            new CssAtMediaEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }
}

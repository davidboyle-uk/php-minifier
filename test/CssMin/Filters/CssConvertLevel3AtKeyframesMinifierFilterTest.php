<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Tokens\CssAtKeyframesStartToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetStartToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetDeclarationToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesRulesetEndToken;
use PHPMin\CssMin\Tokens\CssAtKeyframesEndToken;
use PHPMin\CssMin\Filters\CssConvertLevel3AtKeyframesMinifierFilter;

/**
 * Tests for CssConvertLevel3AtKeyframesMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssConvertLevel3AtKeyframesMinifierFilterTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../data/CssMin/Filters';

    /* CssConvertLevel3AtKeyframesMinifierFilter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => false,
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => false,
        "RemoveEmptyAtBlocks"           => false,
        "ConvertLevel3Properties"       => false,
        "ConvertLevel3AtKeyframes"      => true,
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

        $this->sut = new CssConvertLevel3AtKeyframesMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            ['RemoveSource' => true]
        );
    }

    public function testCssConvertLevel3AtKeyframesMinifierFilterMinify()
    {
        $inputFile = $this->dataDir . '/CssConvertLevel3AtKeyframesMinifierFilter-minify-in.css';
        $outputFile = $this->dataDir . '/CssConvertLevel3AtKeyframesMinifierFilter-minify-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }

    public function testCssConvertLevel3AtKeyframesMinifierFilterMinification()
    {
        $tokens = [
            new CssAtKeyframesStartToken('mymove', 'keyframes'),
            new CssAtKeyframesRulesetStartToken(['from']),
            new CssAtKeyframesRulesetDeclarationToken(null, null, 'top', '0px'),
            new CssAtKeyframesRulesetEndToken(),
            new CssAtKeyframesRulesetStartToken(['to']),
            new CssAtKeyframesRulesetDeclarationToken(null, null, 'top', '200px'),
            new CssAtKeyframesRulesetEndToken(),
            new CssAtKeyframesEndToken()
        ];
        $expected = 16;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }
}

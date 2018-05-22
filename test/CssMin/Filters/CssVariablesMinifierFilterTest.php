<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Filters\CssVariablesMinifierFilter;
use PHPMin\CssMin\Tokens\CssAtVariablesStartToken;
use PHPMin\CssMin\Tokens\CssAtVariablesDeclarationToken;
use PHPMin\CssMin\Tokens\CssAtVariablesEndToken;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssVariablesMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssVariablesMinifierFilterTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../data/CssMin/Filters';

    /* CssVariablesMinifierFilter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => false,
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => false,
        "RemoveEmptyAtBlocks"           => false,
        "ConvertLevel3Properties"       => false,
        "ConvertLevel3AtKeyframes"      => false,
        "Variables"                     => true,
        "RemoveLastDelarationSemiColon" => false
    ];

    /* Plugin Configuration */
    protected $plugins = [
        "Variables"                     => true, //needed for this test
        "ConvertFontWeight"             => false,
        "ConvertHslColors"              => false,
        "ConvertRgbColors"              => false,
        "ConvertNamedColors"            => false,
        "CompressColorValues"           => false,
        "CompressUnitValues"            => false,
        "CompressExpressionValues"      => false
    ];

    public function setUp()
    {
        $this->sut = new CssVariablesMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            []
        );
    }

    public function testCssVariablesMinifierFilterMinify()
    {
        $inputFile = $this->dataDir . '/CssVariablesMinifierFilter-minify-in.css';
        $outputFile = $this->dataDir . '/CssVariablesMinifierFilter-minify-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);

        $minifier = new CssMin(
            $testInput,
            $this->filters,
            $this->plugins
        );
        $minifier->setVerbose(true);

        $actual = $minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssVariablesMinifierFilterMissingPluginMinify()
    {
        $inputFile = $this->dataDir . '/CssVariablesMinifierFilter-minify-in.css';
        $outputFile = $this->dataDir . '/CssVariablesMinifierFilterMissingPlugin-minify-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);

        $this->plugins["Variables"] = false;

        $minifier = new CssMin(
            $testInput,
            $this->filters,
            $this->plugins
        );
        
        $actual = $minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }

    public function testCssVariablesMinifierFilter()
    {
        $tokens = [
            new CssAtVariablesStartToken(),
            new CssAtVariablesEndToken(),
        ];
        $expected = 2;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssVariablesMinifierFilterDeclaration()
    {
        $tokens = [
            new CssAtVariablesStartToken(),
            new CssAtVariablesDeclarationToken('padding', '10px'),
            new CssAtVariablesEndToken(),
        ];
        $expected = 3;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    public function testCssVariablesMinifierFilterVariables()
    {
        $tokens = [
            new CssAtVariablesStartToken(),
            new CssAtVariablesDeclarationToken('--main-bg-color', 'brown'),
            new CssAtVariablesDeclarationToken('background-color', 'var(--main-bg-color)'),
            new CssAtVariablesEndToken(),
        ];
        $expected = 4;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }
}

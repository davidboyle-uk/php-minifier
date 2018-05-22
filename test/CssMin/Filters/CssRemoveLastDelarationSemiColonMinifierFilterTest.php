<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Filters\CssRemoveLastDelarationSemiColonMinifierFilter;

/**
 * Tests for CssRemoveLastDelarationSemiColonMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssRemoveLastDelarationSemiColonMinifierFilterTest extends TestCase
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
        "ConvertLevel3AtKeyframes"      => false,
        "Variables"                     => false,
        "RemoveLastDelarationSemiColon" => true
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

        $this->sut = new CssRemoveLastDelarationSemiColonMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            ['RemoveSource' => true]
        );
    }

    public function testCssConvertLevel3PropertiesMinifierFilterMinify()
    {
        $inputFile = $this->dataDir . '/CssRemoveLastDelarationSemiColonMinifierFilter-minify-in.css';
        $outputFile = $this->dataDir . '/CssRemoveLastDelarationSemiColonMinifierFilter-minify-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);

        $actual = $this->minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }
}

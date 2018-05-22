<?php

namespace PHPMin\CssMin\Test\Plugins\Minifiers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\Plugins\Minifiers\CssConvertHslColorsMinifierPlugin;

/**
 * Tests for CssConvertHslColorsMinifierPlugin
 *
 * @package CssMin/Test/Plugins/Minifiers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssConvertHslColorsMinifierPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Minifiers';

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
        "ConvertHslColors"              => true,
        "ConvertRgbColors"              => false,
        "ConvertNamedColors"            => false,
        "CompressColorValues"           => false,
        "CompressUnitValues"            => false,
        "CompressExpressionValues"      => false
    ];

    public function testCssConvertHslColorsPluginMinify()
    {
        $inputFile = $this->dataDir . '/CssConvertHslColorsMinifierPlugin-minify-in.css';
        $outputFile = $this->dataDir . '/CssConvertHslColorsMinifierPlugin-minify-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);

        $minifier = new CssMin();
        $minifier->setVerbose(true);

        $actual = $minifier->minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }
}

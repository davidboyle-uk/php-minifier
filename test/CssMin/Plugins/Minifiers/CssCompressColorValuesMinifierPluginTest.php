<?php

namespace PHPMin\CssMin\Test\Plugins\Minifiers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\Plugins\Minifiers\CssCompressColorValuesMinifierPlugin;

/**
 * Tests for CssCompressColorValuesMinifierPlugin
 *
 * @package CssMin/Test/Plugins/Minifiers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssCompressColorValuesMinifierPluginTest extends TestCase
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
        "ConvertHslColors"              => false,
        "ConvertRgbColors"              => false,
        "ConvertNamedColors"            => false,
        "CompressColorValues"           => true,
        "CompressUnitValues"            => false,
        "CompressExpressionValues"      => false
    ];

    public function testCssCompressColorValuesMinifierPluginMinify()
    {
        $inputFile = $this->dataDir . '/CssCompressColorValuesMinifierPlugin-minify-in.css';
        $outputFile = $this->dataDir . '/CssCompressColorValuesMinifierPlugin-minify-out.css';

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

<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssExpressionParserPlugin;

/**
 * Tests for CssExpressionParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssExpressionParserPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Parsers';

    /* Plugin Configuration */
    protected $plugins = [
        "Comment"                       => false,
        "String"                        => false,
        "Url"                           => false,
        "Expression"                    => true,
        "Ruleset"                       => false,
        "AtCharset"                     => false,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];
    
    public function testCssExpressionParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssExpressionParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssExpressionParserPlugin-parse-out.ser';

        $testInput = file_get_contents($inputFile);
        $expected = unserialize(file_get_contents($outputFile));

        $minifier = new CssMin();
        $minifier->setVerbose(true);

        $actual = $minifier->parse(
            $testInput,
            $this->plugins
        );

        $this->assertSame(serialize($expected), serialize($actual));
    }

    public function testCssExpressionParserPluginClosingBraceParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssExpressionParserPlugin($parser);

        $index = 17;
        $char = '(';
        $previousChar = '.';
        $state = "T_EXPRESSION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssExpressionParserPluginClosingBraceAltParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssExpressionParserPlugin($parser);

        $index = 5;
        $char = '(';
        $previousChar = '.';
        $state = "T_EXPRESSION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}

<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssAtCharsetParserPlugin;

/**
 * Tests for CssAtCharsetParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtCharsetParserPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Parsers';

    /* Plugin Configuration */
    protected $plugins = [
        "Comment"                       => false,
        "String"                        => false,
        "Url"                           => false,
        "Expression"                    => false,
        "Ruleset"                       => false,
        "AtCharset"                     => true,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];

    public function testCssAtCharsetParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssAtCharsetParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssAtCharsetParserPlugin-parse-out.ser';

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

    public function testCssAtCharsetParserPluginAltParse()
    {
        $expected = false;

        $parser = new CssParser();
        $plugin = new CssAtCharsetParserPlugin($parser);

        $index = 2;
        $char = 'a';
        $previousChar = '.';
        $state = "T_DOCUMENT";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}

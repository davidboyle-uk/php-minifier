<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssAtPageParserPlugin;

/**
 * Tests for CssAtPageParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtPageParserPluginTest extends TestCase
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
        "AtCharset"                     => false,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => true,
        "AtVariables"                   => false
    ];

    public function testCssAtPageParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssAtPageParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssAtPageParserPlugin-parse-out.ser';

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

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssAtPageParserPluginColonParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtPageParserPlugin($parser);

        $index = 0;
        $char = ':';
        $previousChar = '.';
        $state = "T_AT_PAGE_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtPageParserPluginBufferParse()
    {
        $expected = false;

        $parser = new CssParser();
        $parser->setBuffer('filter');
        $plugin = new CssAtPageParserPlugin($parser);

        $index = 0;
        $char = ':';
        $previousChar = '.';

        $state = "T_AT_PAGE";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_AT_PAGE_DECLARATION";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtPageParserPluginClosingBraceParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtPageParserPlugin($parser);

        $index = 0;
        $char = '}';
        $previousChar = '.';
        $state = "T_AT_PAGE_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}

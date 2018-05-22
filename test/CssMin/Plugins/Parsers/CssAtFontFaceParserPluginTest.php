<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssAtFontFaceParserPlugin;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssAtFontFaceParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtFontFacePluginTest extends TestCase
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
        "AtFontFace"                    => true,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];

    public function testCssAtFontFaceParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssAtFontFacePlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssAtFontFacePlugin-parse-out.ser';

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
    public function testCssAtFontFaceParserPluginAltParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtFontFaceParserPlugin($parser);

        $index = 2;
        $char = ':';
        $previousChar = '.';
        $state = "T_AT_FONT_FACE_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtFontFaceParserPluginParseNoBuffer()
    {
        $expected = false;

        $parser = new CssParser();
        $parser->setBuffer('filter');
        $plugin = new CssAtFontFaceParserPlugin($parser);

        $index = 2;
        $char = ':';
        $previousChar = '.';

        $state = "T_AT_FONT_FACE";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_AT_FONT_FACE_DECLARATION";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtFontFaceParserPluginParseFalse()
    {
        $expected = false;

        $parser = new CssParser();
        $plugin = new CssAtFontFaceParserPlugin($parser);

        $index = 2;
        $char = 'a';
        $previousChar = '.';
        $state = "T_AT_FONT_FACE_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssAtFontFaceParserPluginParseCloseBrace()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssAtFontFaceParserPlugin($parser);

        $index = 10;
        $char = '}';
        $previousChar = ' ';
        $state = "T_AT_FONT_FACE_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}

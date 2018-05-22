<?php

namespace PHPMin\CssMin\Test\Plugins\Parsers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Plugins\Parsers\CssRulesetParserPlugin;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssRulesetParserPlugin
 *
 * @package CssMin/Test/Plugins/Parsers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssRulesetParserPluginTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../../data/CssMin/Plugins/Parsers';

    /* Plugin Configuration */
    protected $plugins = [
        "Comment"                       => false,
        "String"                        => false,
        "Url"                           => true,
        "Expression"                    => false,
        "Ruleset"                       => true,
        "AtCharset"                     => false,
        "AtFontFace"                    => false,
        "AtImport"                      => false,
        "AtKeyframes"                   => false,
        "AtMedia"                       => false,
        "AtPage"                        => false,
        "AtVariables"                   => false
    ];
    
    public function testCssRulesetParserPluginParse()
    {
        $inputFile = $this->dataDir . '/CssRulesetParserPlugin-parse-in.css';
        $outputFile = $this->dataDir . '/CssRulesetParserPlugin-parse-out.ser';

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
    
    public function testCssRulesetParserPluginSvgUrlParse()
    {
        $inputFile = $this->dataDir . '/CssRulesetParserPlugin-svg-url-parse-in.css';
        $outputFile = $this->dataDir . '/CssRulesetParserPlugin-svg-url-parse-out.ser';

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

    public function testCssRulesetParserPluginCommaParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssRulesetParserPlugin($parser);

        $index = 17;
        $char = ',';
        $previousChar = '.';
        $state = "T_AT_MEDIA";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssRulesetParserPluginColonParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssRulesetParserPlugin($parser);

        $index = 17;
        $char = ':';
        $previousChar = '.';
        $state = "T_RULESET_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssRulesetParserPluginBufferParse()
    {
        $expected = false;

        $parser = new CssParser();
        $parser->setBuffer('filter');
        $plugin = new CssRulesetParserPlugin($parser);

        $index = 17;
        $char = ':';
        $previousChar = '.';

        $state = "T_RULESET";
        $plugin->parse($index, $char, $previousChar, $state);

        $state = "T_RULESET_DECLARATION";
        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssRulesetParserPluginOpeningBraceParse()
    {
        $expected = true;

        $parser = new CssParser('.test {}');
        $plugin = new CssRulesetParserPlugin($parser);

        $index = 17;
        $char = '{';
        $previousChar = '.';
        $state = "T_RULESET::SELECTORS";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssRulesetParserPluginClosingBraceRulesetParse()
    {
        $expected = true;

        $parser = new CssParser();
        $plugin = new CssRulesetParserPlugin($parser);

        $index = 17;
        $char = '}';
        $previousChar = '.';
        $state = "T_RULESET_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }

    public function testCssRulesetParserPluginFalseParse()
    {
        $expected = false;

        $parser = new CssParser();
        $plugin = new CssRulesetParserPlugin($parser);

        $index = 17;
        $char = 'a';
        $previousChar = '.';
        $state = "T_RULESET_DECLARATION";

        $actual = $plugin->parse($index, $char, $previousChar, $state);

        $this->assertSame($expected, $actual);
    }
}

<?php

namespace PHPMin\Test;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssError;
use PHPMin\CssMin\CssParser;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssMin
 *
 * @package CssMin/Test
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssMinTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../data/CssMin';

    /* CssMin */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => ['BasePath' => __DIR__ . '/../data/CssMin/imports'],
        "RemoveComments"                => true,
        "RemoveEmptyRulesets"           => true,
        "RemoveEmptyAtBlocks"           => true,
        "ConvertLevel3Properties"       => false,
        "ConvertLevel3AtKeyframes"      => false,
        "Variables"                     => true,
        "RemoveLastDelarationSemiColon" => true
    ];

    /* Plugin Configuration */
    protected $plugins = [
        "Variables"                => true,
        "ConvertFontWeight"        => true,
        "ConvertHslColors"         => true,
        "ConvertRgbColors"         => true,
        "ConvertNamedColors"       => true,
        "CompressColorValues"      => true,
        "CompressUnitValues"       => true,
        "CompressExpressionValues" => true
    ];

    protected function setUp()
    {
        $this->sut = new CssMin(
            null,
            $this->filters,
            $this->plugins
        );
    }

    public function testCssMinParse()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.css';
        $outputFile = $this->dataDir . '/basic-parse-out.ser';

        $testInput = file_get_contents($inputFile);
        $expected = unserialize(file_get_contents($outputFile));
        
        $actual = $this->sut::parse($testInput);

        $this->assertSame(serialize($expected), serialize($actual));
    }

    public function testCssMinVerboseParse()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.css';
        $outputFile = $this->dataDir . '/basic-parse-out.ser';

        $testInput = file_get_contents($inputFile);
        $expected = unserialize(file_get_contents($outputFile));
        
        $this->sut->setVerbose(true);
        $actual = $this->sut::parse($testInput);

        $this->assertSame(serialize($expected), serialize($actual));
    }

    public function testCssMinTriggerError()
    {
        $error = new CssError(
            __FILE__,
            __LINE__,
            __METHOD__ . ": Test error message"
        );

        $this->sut->setVerbose(false);
        $this->sut->triggerError($error);
        
        $expected = true;
        $actual = $this->sut->hasErrors();

        $this->assertSame($expected, $actual);
        
        $expected = [$error];
        $actual = $this->sut->getErrors();

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssMinTriggerErrorVerbose()
    {
        $error = new CssError(
            __FILE__,
            __LINE__,
            __METHOD__ . ": Test error message"
        );

        $this->sut->setVerbose(true);
        $this->sut->triggerError($error);
        
        $expected = true;
        $actual = $this->sut->hasErrors();

        $this->assertSame($expected, $actual);
    }

    public function testCssMinParseErrors()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.css';

        $testInput = file_get_contents($inputFile);
        
        $this->sut::parse($testInput);
        
        $expected = false;
        $actual = $this->sut->hasErrors();

        $this->assertSame($expected, $actual);
        
        $expected = [];
        $actual = $this->sut->getErrors();

        $this->assertSame($expected, $actual);
    }

    public function testCssMinMinify()
    {
        $inputFile = $this->dataDir . '/basic-minification-in.css';
        $outputFile = $this->dataDir . '/basic-minification-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }

    public function testCssMinBootstrapMinify()
    {
        $inputFile = $this->dataDir . '/bootstrap.min.css';
        $outputFile = $this->dataDir . '/bootstrap.min-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);

        $actual = $this->sut::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }

    public function testDoubleCssMinification()
    {
        $inputFile = $this->dataDir . '/basic-minification-out.css';
        $outputFile = $this->dataDir . '/double-minification-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->sut::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }
}

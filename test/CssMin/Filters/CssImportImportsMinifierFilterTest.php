<?php

namespace PHPMin\Test\CssMin\Filters;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Filters\CssImportImportsMinifierFilter;
use PHPMin\CssMin\Tokens\CssAtImportToken;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssImportImportsMinifierFilter
 *
 * @package CssMin/Test/Filters
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssImportImportsMinifierFilterTest extends TestCase
{
    /* Test Data Folder Path */
    protected $dataDir = __DIR__ . '/../../data/CssMin/Filters';

    /* CssImportImportsMinifierFilter */
    protected $sut;

    /* Filters Configuration */
    protected $filters = [
        "ImportImports"                 => ['BasePath' => __DIR__ . '/../../data/CssMin'],
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
        $this->minifier->setVerbose(true);

        $this->sut = new CssImportImportsMinifierFilter(
            new CssMinifier(
                null,
                $this->filters,
                $this->plugins
            ),
            ['BasePath' => __DIR__ . '/../../data/CssMin']
        );
    }

    public function testCssImportImportsMinifierFilterMinify()
    {
        $inputFile = $this->dataDir . '/CssImportImportsMinifierFilter-minify-in.css';
        $outputFile = $this->dataDir . '/CssImportImportsMinifierFilter-minify-out.css';

        $testInput = file_get_contents($inputFile);
        $expected = file_get_contents($outputFile);
        
        $actual = $this->minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssImportImportsMinifierFilterMinifyMissingBasePathException()
    {
        $this->filters["ImportImports"] = true;

        $inputFile = $this->dataDir . '/CssImportImportsMinifierFilter-minify-in.css';

        $testInput = file_get_contents($inputFile);
        
        $actual = $this->minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );
    }

    public function testCssImportImportsMinifierFilterMinifyMissingBasePath()
    {
        $expectedNumErrors = 1;

        $this->filters["ImportImports"] = true;
        $this->minifier->setVerbose(false);

        $inputFile = $this->dataDir . '/CssImportImportsMinifierFilter-minify-in.css';

        $testInput = file_get_contents($inputFile);
        
        $actual = $this->minifier::Minify(
            $testInput,
            $this->filters,
            $this->plugins
        );

        $this->assertSame($expectedNumErrors, sizeof($this->minifier->getErrors()));
    }

    public function testCssImportImportsMinifierFilterMinification()
    {
        $tokens = [
            new CssAtImportToken('Filters/CssImportImportsMinifierFilter-minify-in.css', ['screen', 'all']),
        ];
        $expected = 17;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssImportImportsMinifierFilterDuplicateImportException()
    {
        $tokens = [
            new CssAtImportToken('Filters/CssImportImportsMinifierFilter-minify-in.css', ['screen', 'all']),
            new CssAtImportToken('Filters/CssImportImportsMinifierFilter-minify-in.css', ['screen', 'all']),
        ];

        $this->sut->apply($tokens);
    }

    public function testCssImportImportsMinifierFilterDuplicateImport()
    {
        $expectedNumErrors = 1;
        
        $this->minifier->clearErrors();
        $this->minifier->setVerbose(false);

        $tokens = [
            new CssAtImportToken('Filters/CssImportImportsMinifierFilter-minify-in.css', ['screen', 'all']),
            new CssAtImportToken('Filters/CssImportImportsMinifierFilter-minify-in.css', ['screen', 'all']),
        ];

        $this->sut->apply($tokens);

        $this->assertSame($expectedNumErrors, sizeof($this->minifier->getErrors()));
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssImportImportsMinifierFilterImportNotFoundException()
    {
        $tokens = [
            new CssAtImportToken('i-dont-exist.css', [])
        ];

        $actual = $this->sut->apply($tokens);
    }

    public function testCssImportImportsMinifierFilterImportNotFound()
    {
        $expectedNumErrors = 1;
        $this->minifier->clearErrors();
        $this->minifier->setVerbose(false);

        $tokens = [
            new CssAtImportToken('i-dont-exist.css', [])
        ];
        $expected = 1;

        $actual = $this->sut->apply($tokens);

        $this->assertSame($expectedNumErrors, sizeof($this->minifier->getErrors()));
    }

    public function testCssImportImportsMinifierFilterMediaRules()
    {
        $tokens = [
            new CssAtImportToken('Filters/CssImportImportsMinifierFilterMediaImport.css', ['print']),
        ];

        $actual = $this->sut->apply($tokens);
    }

    public function testCssImportImportsMinifierFilterMediaRulesMismatch()
    {
        $tokens = [
            new CssAtImportToken('Filters/CssImportImportsMinifierFilterMediaImport.css', ['screen']),
        ];

        $actual = $this->sut->apply($tokens);
    }
}

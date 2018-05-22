<?php

namespace PHPMin\CssMin\Test\Plugins\Minifiers;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Plugins\Minifiers\CssVariablesMinifierPlugin;
use PHPMin\CssMin\Tokens\CssRulesetDeclarationToken;

/**
 * Tests for CssVariablesMinifierPlugin
 *
 * @package CssMin/Test/Plugins/Minifiers
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssVariablesMinifierPluginTest extends TestCase
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
        "Variables"                     => true,
        "RemoveLastDelarationSemiColon" => false
    ];

    /* Plugin Configuration */
    protected $plugins = [
        "Variables"                     => true,
        "ConvertFontWeight"             => false,
        "ConvertHslColors"              => false,
        "ConvertRgbColors"              => false,
        "ConvertNamedColors"            => false,
        "CompressColorValues"           => false,
        "CompressUnitValues"            => false,
        "CompressExpressionValues"      => false
    ];

    public function testCssVariablesMinifierPluginMinify()
    {
        $inputFile = $this->dataDir . '/CssVariablesMinifierPlugin-minify-in.css';
        $outputFile = $this->dataDir . '/CssVariablesMinifierPlugin-minify-out.css';

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

    public function testCssVariablesMinifierPluginApply()
    {
        $expected = true;

        $minifier = new CssMinifier(
            null,
            $this->filters,
            $this->plugins
        );

        $plugin = new CssVariablesMinifierPlugin($minifier);

        $variables = [
            'all' => [
                '--main-bg-color' => 'brown'
            ]
        ];
        $plugin->setVariables($variables);

        $token = new CssRulesetDeclarationToken(
            'background-color',
            'var(--main-bg-color)',
            ['screen and (min-width: 480px)'],
            false,
            false
        );

        $actual = $plugin->apply($token);

        $this->assertSame($variables, $plugin->getVariables());
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssVariablesMinifierPluginApplyErrorException()
    {
        $min = new CssMin();
        $min->setVerbose(true);
        $min->clearErrors();

        $minifier = new CssMinifier(
            null,
            $this->filters,
            $this->plugins
        );

        $plugin = new CssVariablesMinifierPlugin($minifier);

        $token = new CssRulesetDeclarationToken(
            'background-color',
            'var(--main-bg-color)',
            ['screen and (min-width: 480px)'],
            false,
            false
        );

        $actual = $plugin->apply($token);
    }

    public function testCssVariablesMinifierPluginApplyError()
    {
        $expected = true;
        $expectedNumErrors = 1;

        $min = new CssMin();
        $min->setVerbose(false);
        $min->clearErrors();

        $minifier = new CssMinifier(
            null,
            $this->filters,
            $this->plugins
        );

        $plugin = new CssVariablesMinifierPlugin($minifier);

        $token = new CssRulesetDeclarationToken(
            'background-color',
            'var(--main-bg-color)',
            ['screen and (min-width: 480px)'],
            false,
            false
        );

        $actual = $plugin->apply($token);

        $this->assertSame(get_class($token), 'PHPMin\CssMin\Tokens\CssNullToken');

        $this->assertSame($expectedNumErrors, sizeof($min->getErrors()));
        $this->assertSame($expected, $actual);
    }
}

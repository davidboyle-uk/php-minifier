<?php

namespace PHPMin\Test;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin;
use PHPMin\CssMin\CssMinifier;
use PHPMin\CssMin\Exceptions\CssMinException;

/**
 * Tests for CssMinfier
 *
 * @package CssMin/CssMinfier
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssMinfierTest extends TestCase
{
    public function testCssMinifierBadFilterNameError()
    {
        $expectedNumErrors = 1;

        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(false);

        $sut = new CssMinifier(
            null,
            [],
            ['NonExistentFilterName' => true]
        );

        $this->assertSame($expectedNumErrors, sizeof($minifier->getErrors()));
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssMinifierBadFilterNameException()
    {
        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(true);

        $sut = new CssMinifier(
            null,
            [],
            ['NonExistentFilterName' => true]
        );
    }

    public function testCssMinifierBadPluginNameError()
    {
        $expectedNumErrors = 1;

        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(false);

        $sut = new CssMinifier(
            null,
            ['NonExistentPluginName' => true],
            []
        );

        $this->assertSame($expectedNumErrors, sizeof($minifier->getErrors()));
    }

    /**
     * @expectedException PHPMin\CssMin\Exceptions\CssMinException
     */
    public function testCssMinifierBadPluginNameException()
    {
        $minifier = new CssMin();
        $minifier->clearErrors();
        $minifier->setVerbose(true);

        $sut = new CssMinifier(
            null,
            ['NonExistentPluginName' => true],
            []
        );
    }
}

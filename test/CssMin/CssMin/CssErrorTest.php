<?php

namespace PHPMin\Test;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin\CssError;

/**
 * Tests for CssError
 *
 * @package CssMin/Test
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssErrorTest extends TestCase
{
    /* CssError */
    protected $sut;

    public function testCssError()
    {
        $this->sut = new CssError(
            __FILE__,
            __LINE__,
            __METHOD__ . ": Test error message",
            'Source'
        );

        $expected = 'PHPMin\Test\CssErrorTest::testCssError: '
                    . 'Test error message: <br /><code>Source</code><br />'
                    . 'in file ' . __DIR__ . '/CssErrorTest.php at line 25';
        $actual = $this->sut->__toString();

        $this->assertSame($expected, $actual);
    }

    public function testCssErrorNoSource()
    {
        $this->sut = new CssError(
            __FILE__,
            __LINE__,
            __METHOD__ . ": Test error message"
        );

        $expected = 'PHPMin\Test\CssErrorTest::testCssErrorNoSource: '
                    . 'Test error message<br />'
                    . 'in file ' . __DIR__ . '/CssErrorTest.php at line 42';
        $actual = $this->sut->__toString();
        
        $this->assertSame($expected, $actual);
    }
}

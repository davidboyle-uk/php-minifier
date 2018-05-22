<?php

namespace PHPMin\CssMin\Test\Tokens;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin\Tokens\CssNullToken;

/**
 * Tests for CssNullToken
 *
 * @package CssMin/Test/Tokens
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssNullTokenTest extends TestCase
{
    public function testCssNullToken()
    {
        $token = new CssNullToken();
        $this->assertSame($token->__toString(), "");
    }
}

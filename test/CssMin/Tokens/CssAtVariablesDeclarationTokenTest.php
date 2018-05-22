<?php

namespace PHPMin\CssMin\Test\Tokens;

use PHPUnit\Framework\TestCase;
use PHPMin\CssMin\Tokens\CssAtVariablesDeclarationToken;

/**
 * Tests for CssAtVariablesDeclarationToken
 *
 * @package CssMin/Test/Tokens
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CssAtVariablesDeclarationTokenTest extends TestCase
{
    public function testCssAtVariablesDeclarationToken()
    {
        $token = new CssAtVariablesDeclarationToken(
            'padding',
            '10px',
            true,
            true
        );
        $this->assertSame($token->__toString(), "");
    }
}

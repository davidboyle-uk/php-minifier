<?php

namespace PHPMin\Test;

use PHPUnit\Framework\TestCase;

/**
 * Custom Error extension of standard PHPUnit TestCase
 * Needed when using trigger_error rather then throw exception in CssError
 * Unused but left in repo for reference
 *
 * @package PHPMin/Test
 * @author  2018 David Bolye <https://github.com/dbx123>
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.0.1
 */
class CustomErrorTestCase extends TestCase
{
    /* Errors array */
    public $errors;

    protected function setUp()
    {
        $this->errors = [];
        set_error_handler([$this, "errorHandler"]);
    }

    protected function tearDown()
    {
        restore_error_handler();
    }

    public function errorHandler($errorNum, $errorMsg, $errorFile, $errorLine, $errorContext)
    {
        $this->errors[] = compact(
            "errorNum",
            "errorMsg",
            "errorFile",
            "errorLine",
            "errorContext"
        );
    }

    public function assertError($errorNum, $errorMsg)
    {
        foreach ($this->errors as $error) {
            if ($error["errorNum"] === $errorNum
                && $error["errorMsg"] === $errorMsg
            ) {
                return;
            }
        }

        $this->fail(
            "Error with level "
            . $errorNum
            . " and message '"
            . $errorMsg
            . "' not found in ",
            var_export($this->errors, true)
        );
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

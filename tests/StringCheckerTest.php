<?php

namespace Tests;

use Checker\StringChecker;
use PHPUnit\Framework\TestCase;
use http\Exception\InvalidArgumentException;


/**
 * Class StringCheckerTest
 */
class StringCheckerTest extends TestCase
{
    private const DEBUG_MODE = 0;
    private const POSITIVE_STRING_CASES =
        [
            '()()()()((()()))',
            '((((()()(()())))))',
            '( ( ) ) \n ()()'
        ];

    private const NEGATIVE_STRING_CASES =
        [
            '()()()()((()())))',
            '((((()()(()())))))1',
            '( ( ) ) \n ()()*'
        ];

    /**
     * Positive test
     */
    public function testPositive()
    {
        foreach (self::POSITIVE_STRING_CASES as $string) {
            $checker = new StringChecker($string, self::DEBUG_MODE);
            $this->assertEquals(true, $checker->stringCheck());
        }
    }

    /**
     * Negative test
     * @expectedException InvalidArgumentException
     */
    public function testNegative()
    {
        foreach (self::NEGATIVE_STRING_CASES as $string) {
            $checker = new StringChecker($string, self::DEBUG_MODE);
            $this->assertEquals(false, $checker->stringCheck());
        }
    }
}
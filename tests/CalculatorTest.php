<?php

namespace App\Tests;

use App\Service\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();
    }

    public function testSum()
    {
        $this->assertEquals(5, $this->calculator->sum(2, 3));
        $this->assertEquals(-1, $this->calculator->sum(2, -3));
    }

    public function testSumWithMax()
    {
        $this->expectException(\OverflowException::class);
        $this->calculator->sum(PHP_FLOAT_MAX, PHP_FLOAT_MAX);
    }
}

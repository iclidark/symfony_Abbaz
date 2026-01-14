<?php

namespace App\Tests;

use App\Service\Exercises;
use PHPUnit\Framework\TestCase;

class ExercisesTest extends TestCase
{
    private $exercises;

    protected function setUp(): void
    {
        $this->exercises = new Exercises();
    }

    public function testRacineCarreeException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->exercises->racineCarree(-1);
    }

    public function testRacineCarreePositive()
    {
        $this->assertEquals(2, $this->exercises->racineCarree(4));
    }

    public function testDiviserException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->exercises->diviser(1, 0);
    }

    public function testDiviser()
    {
        $this->assertEquals(2, $this->exercises->diviser(4, 2));
    }

    public function testString()
    {
        $this->assertIsString($this->exercises->string());
    }

    public function testEstPair()
    {
        $this->assertTrue($this->exercises->estPair(2));
        $this->assertFalse($this->exercises->estPair(1));
    }

    public function testConcatenation()
    {
        $this->assertEquals("HelloWorld", $this->exercises->concatenation("Hello", "World"));
    }

    public function testExtraireElementsPairs()
    {
        $this->assertEquals([2, 4], $this->exercises->extraireElementsPairs([1, 2, 3, 4, 5]));
    }
}

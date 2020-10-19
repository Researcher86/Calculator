<?php

declare(strict_types=1);

namespace Tests;

use App\ComplexCalculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private ComplexCalculator $calc;

    protected function setUp(): void
    {
        $this->calc = new ComplexCalculator();
    }

    public function testThrowSuffixMismatch()
    {
        $this->expectExceptionMessage('Suffix Mismatch');
        $this->calc->add('+i', '+j');
    }

    public function testThrowDivisionByZero()
    {
        $this->expectExceptionMessage('Division by zero');
        $this->calc->div('+i', '0.0+0.0i');
    }

    /**
     * @dataProvider providerAdd
     * @param string $a
     * @param string $b
     * @param string $expected
     */
    public function testAdd($a, $b, $expected)
    {
        $complexString = $this->calc->add($a, $b);
        self::assertEquals($expected, $complexString);
    }

    /**
     * @dataProvider providerSubtract
     * @param string $a
     * @param string $b
     * @param string $expected
     */
    public function testSub($a, $b, $expected)
    {
        $complexString = $this->calc->sub($a, $b);
        self::assertEquals($expected, $complexString);
    }

    /**
     * @dataProvider providerMultiply
     * @param string $a
     * @param string $b
     * @param string $expected
     */
    public function testMul($a, $b, $expected)
    {
        $complexString = $this->calc->mul($a, $b);
        self::assertEquals($expected, $complexString);
    }

    /**
     * @dataProvider providerDivide
     * @param string $a
     * @param string $b
     * @param string $expected
     */
    public function testDiv($a, $b, $expected)
    {
        $complexString = $this->calc->div($a, $b);
        self::assertEquals($expected, $complexString);
    }

    public function providerAdd()
    {
        return [
            ['+i', '-i', '0.0'],
            ['1-i', '2+i', '3'],
            ['1-1i', '2+1i', '3'],
            ['1.23-4.56i', '2.34+5.67i', '3.57+1.11i'],
        ];
    }

    public function providerSubtract()
    {
        return [
            ['1-1i', '2+1i', '-1-2i'],
            ['1.23-4.56i', '2.34+5.67i', '-1.11-10.23i'],
            ['2.34+5.67i', '1.23-4.56i', '1.11+10.23i'],
        ];
    }

    public function providerMultiply()
    {
        return [
            ['1-1i', '2+1i', '3-i'],
            ['1.23-4.56i', '2.34+5.67i', '28.7334-3.6963i']
        ];
    }

    public function providerDivide()
    {
        return [
            ['1-1i', '2+1i', '0.2-0.6i'],
            ['1.23-4.56i', '2.34+5.67i', '-0.61069250089702-0.46896304269824i']
        ];
    }
}

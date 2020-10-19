<?php

declare(strict_types=1);

namespace Tests;

use App\ComplexNumber;
use PHPUnit\Framework\TestCase;

class ComplexNumberTest extends TestCase
{
    public function testCreateFromString()
    {
        $complexNumber = ComplexNumber::createFromString('1-1i');
        self::assertEquals(1, $complexNumber->getReal());
        self::assertEquals(-1, $complexNumber->getImaginary());
        self::assertEquals('i', $complexNumber->getSuffix());

        $complexNumber = ComplexNumber::createFromString('1i');
        self::assertEquals(0, $complexNumber->getReal());
        self::assertEquals(1, $complexNumber->getImaginary());
        self::assertEquals('i', $complexNumber->getSuffix());
    }

    public function testCreateFromElements()
    {
        $complexNumber = ComplexNumber::createFromElements(1, -1, 'i');
        self::assertEquals(1, $complexNumber->getReal());
        self::assertEquals(-1, $complexNumber->getImaginary());
        self::assertEquals('i', $complexNumber->getSuffix());
    }

    public function testFormat()
    {
        $complexNumber = ComplexNumber::createFromElements(1, -2, 'i');
        self::assertEquals('1-2i', $complexNumber->format());
        $complexNumber = ComplexNumber::createFromElements(1, -1, 'i');
        self::assertEquals('1-i', $complexNumber->format());
        $complexNumber = ComplexNumber::createFromElements(1, -1, 'i');
        self::assertEquals('1-i', $complexNumber);
    }

    public function testThrowInvalidComplexNumber()
    {
        $this->expectExceptionMessage('Invalid complex number');
        ComplexNumber::createFromString('*k');
    }
}

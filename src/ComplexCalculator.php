<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

class ComplexCalculator
{
    public function add(string $complexString1, string $complexString2): string
    {
        [$complexNumber1, $complexNumber2] = $this->parseComplex($complexString1, $complexString2);

        $this->checkComplexNumber($complexNumber1, $complexNumber2);

        $real = $complexNumber1->getReal() + $complexNumber2->getReal();
        $imaginary = $complexNumber1->getImaginary() + $complexNumber2->getImaginary();
        $suffix = $imaginary == 0.0 ? '' : \max($complexNumber1->getSuffix(), $complexNumber2->getSuffix());

        return $this->convertToString($real, $imaginary, $suffix);
    }

    public function sub(string $complexString1, string $complexString2): string
    {
        [$complexNumber1, $complexNumber2] = $this->parseComplex($complexString1, $complexString2);

        $this->checkComplexNumber($complexNumber1, $complexNumber2);

        $real = $complexNumber1->getReal() - $complexNumber2->getReal();
        $imaginary = $complexNumber1->getImaginary() - $complexNumber2->getImaginary();
        $suffix = $imaginary == 0.0 ? '' : \max($complexNumber1->getSuffix(), $complexNumber2->getSuffix());

        return $this->convertToString($real, $imaginary, $suffix);
    }

    public function mul(string $complexString1, string $complexString2): string
    {
        [$complexNumber1, $complexNumber2] = $this->parseComplex($complexString1, $complexString2);

        $this->checkComplexNumber($complexNumber1, $complexNumber2);

        $real = ($complexNumber1->getReal() * $complexNumber2->getReal())
              - ($complexNumber1->getImaginary() * $complexNumber2->getImaginary());

        $imaginary = ($complexNumber1->getImaginary() * $complexNumber2->getReal())
                   + ($complexNumber1->getReal() * $complexNumber2->getImaginary());

        $suffix = $imaginary == 0.0 ? '' : \max($complexNumber1->getSuffix(), $complexNumber2->getSuffix());

        return $this->convertToString($real, $imaginary, $suffix);
    }

    public function div(string $complexString1, string $complexString2): string
    {
        [$complexNumber1, $complexNumber2] = $this->parseComplex($complexString1, $complexString2);

        $this->checkComplexNumber($complexNumber1, $complexNumber2);

        if ($complexNumber2->getReal() == 0.0 && $complexNumber2->getImaginary() == 0.0) {
            throw new RuntimeException('Division by zero');
        }

        $delta1 = ($complexNumber1->getReal() * $complexNumber2->getReal())
                + ($complexNumber1->getImaginary() * $complexNumber2->getImaginary());

        $delta2 = ($complexNumber1->getImaginary() * $complexNumber2->getReal())
                - ($complexNumber1->getReal() * $complexNumber2->getImaginary());

        $delta3 = ($complexNumber2->getReal() * $complexNumber2->getReal())
                + ($complexNumber2->getImaginary() * $complexNumber2->getImaginary());

        $real = $delta1 / $delta3;
        $imaginary = $delta2 / $delta3;
        $suffix = $imaginary == 0.0 ? '' : \max($complexNumber1->getSuffix(), $complexNumber2->getSuffix());

        return $this->convertToString($real, $imaginary, $suffix);
    }

    protected function checkComplexNumber(ComplexNumber $number1, ComplexNumber $number2): void
    {
        if ($number1->getSuffix() !== $number2->getSuffix()) {
            throw new RuntimeException('Suffix Mismatch');
        }
    }

    protected function convertToString(float $real, float $imaginary, ?string $suffix): string
    {
        $result = ComplexNumber::createFromElements($real, $imaginary, $suffix);
        return $result->format();
    }

    /**
     * @param string $complexString1
     * @param string $complexString2
     * @return ComplexNumber[]
     */
    protected function parseComplex(string $complexString1, string $complexString2): array
    {
        return [
            ComplexNumber::createFromString($complexString1),
            ComplexNumber::createFromString($complexString2)
        ];
    }
}

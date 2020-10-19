<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

class ComplexNumber
{
    public const NUMBER_SPLIT_REGEXP =
        '` ^
            (?P<realValue>                                  # Real part
                [-+]?(\d+\.?\d*|\d*\.?\d+)                    # Real value (integer or float)
                (?P<realValueEx>[Ee][-+]?[0-2]?\d{1,3})?      # Optional real exponent for scientific format
            )
            (?P<imaginaryValue>                             # Imaginary part
                [-+]?(\d+\.?\d*|\d*\.?\d+)                    # Imaginary value (integer or float)
                (?P<imaginaryValueEx>[Ee][-+]?[0-2]?\d{1,3})? # Optional imaginary exponent for scientific format
            )?
            (?P<imaginaryOptional>               # Imaginary part is optional
                (?P<imaginary>[-+]?)               # Imaginary (implicit 1 or -1) only
                (?P<suffix>[ij]?)                  # Imaginary i or j - depending on whether mathematical or engineering
            )
        $`uix';

    private float $real;
    private float $imaginary;
    private string $suffix;

    protected function __construct()
    {
    }

    public static function createFromString(string $complexString): self
    {
        $number = new self();
        [$number->real, $number->imaginary, $number->suffix] = self::parseComplex($complexString);

        return $number;
    }

    public static function createFromElements(float $real, float $imaginary, string $suffix): self
    {
        $number = new self();
        $number->real = $real;
        $number->imaginary = $imaginary;
        $number->suffix = $suffix;

        return $number;
    }

    protected static function parseComplex(string $complexString): array
    {
        $complexString = \str_replace(['+-', '-+', '++', '--'], ['-', '-', '+', '+'], $complexString);
        $validComplex = \preg_match(self::NUMBER_SPLIT_REGEXP, $complexString, $complexParts);

        if (!$validComplex) {
            $validComplex = \preg_match('/^(?P<imaginary>[\-\+]?)(?P<suffix>[ij])$/ui', $complexString, $complexParts);
            if (!$validComplex) {
                throw new RuntimeException('Invalid complex number');
            }
            $imaginary = $complexParts['imaginary'] === '-' ? -1 : 1;

            return [
                0,
                (float) $imaginary,
                $complexParts['suffix']
            ];
        }

        // If we don't have an imaginary part, identify whether it should be +1 or -1...
        if (($complexParts['imaginaryValue'] === '') && ($complexParts['suffix'] !== '')) {
            if ($complexParts['imaginaryOptional'] !== $complexParts['suffix']) {
                $complexParts['imaginaryValue'] = $complexParts['imaginary'] === '-' ? -1 : 1;
            } else {
                // ... or if we have only the real and no imaginary part
                //  (in which case our real should be the imaginary)
                $complexParts['imaginaryValue'] = $complexParts['realValue'];
                $complexParts['realValue'] = 0;
            }
        }

        return [
            (float) $complexParts['realValue'],
            (float) $complexParts['imaginaryValue'],
            !empty($complexParts['suffix']) ? $complexParts['suffix'] : 'i'
        ];
    }

    public function getReal(): float
    {
        return $this->real;
    }

    public function getImaginary(): float
    {
        return $this->imaginary;
    }

    public function getSuffix(): string
    {
        return $this->suffix;
    }

    public function format(): string
    {
        $str = '';
        if ($this->imaginary != 0.0) {
            if (\abs($this->imaginary) != 1.0) {
                $str .= $this->imaginary . $this->suffix;
            } else {
                $str .= (($this->imaginary < 0.0) ? '-' : '') . $this->suffix;
            }
        }

        if ($this->real != 0.0) {
            if (($str) && ($this->imaginary > 0.0)) {
                $str = '+' . $str;
            }
            $str = $this->real . $str;
        }

        if (!$str) {
            $str = '0.0';
        }

        return $str;
    }

    public function __toString(): string
    {
        return $this->format();
    }
}

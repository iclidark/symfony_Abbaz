<?php

namespace App\Service;

use OverflowException;

class Calculator
{
    public function sum(float $a, float $b): float
    {
        $result = $a + $b;
        if (is_infinite($result)) {
            throw new OverflowException("La somme dépasse la valeur maximale autorisée.");
        }
        return $result;
    }
}

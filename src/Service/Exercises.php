<?php

namespace App\Service;

use InvalidArgumentException;

class Exercises
{
    public function racineCarree(float $nombre): float
    {
        if ($nombre < 0) {
            throw new InvalidArgumentException("Le nombre ne peut pas être négatif.");
        }
        return sqrt($nombre);
    }

    public function diviser(float $a, float $b): float
    {
        if ($b == 0) {
            throw new InvalidArgumentException("Le dénominateur ne peut pas être égal à 0.");
        }
        return $a / $b;
    }

    public function string(): string
    {
        return "une chaîne de caractères";
    }

    public function estPair(int $nombre): bool
    {
        return $nombre % 2 == 0;
    }

    public function concatenation(string $a, string $b): string
    {
        return $a . $b;
    }

    public function extraireElementsPairs(array $tableau): array
    {
        return array_values(array_filter($tableau, fn($n) => $n % 2 == 0));
    }
}

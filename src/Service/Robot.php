<?php

declare(strict_types=1);

namespace App\Service;

class Robot
{
    public function getNextCoords(Game $game): ?array
    {
        $availableCoords = $game->getEmptyCells();

        $i = rand(0, count($availableCoords) - 1);

        return $availableCoords[$i] ?? null;
    }
}
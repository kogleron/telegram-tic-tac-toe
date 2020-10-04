<?php

declare(strict_types=1);

namespace App\Service;

class Robot
{
    public function getNextCoords(Game $game): ?array
    {
        $availableCoords = $game->getEmptyCells();

        $i = rand(0, count($availableCoords));

        return $availableCoords[$i] ?? null;
    }
}
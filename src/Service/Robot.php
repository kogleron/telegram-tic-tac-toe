<?php

declare(strict_types=1);

namespace App\Service;

class Robot
{
    private string $opponent;

    public function __construct($opponent = 'x')
    {

        $this->opponent = $opponent;
    }

    public function getNextCoords(Game $game): ?array
    {
        $coords = $this->predictPlayerTurn($this->opponent, $game);
        if ($coords) {
            return $coords;
        }

        return $this->getRandTurn($game);
    }

    public function predictPlayerTurn(string $player, Game $game): ?array
    {
        $field = $game->getField();
        foreach ($field as $rowN => $row) {
            foreach ($row as $columnN => $value) {
                if (2 === $game->countHorizontalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN][$columnN - 1] ?? null)) {
                        return [$columnN - 1, $rowN];
                    }
                    if (' ' === ($field[$rowN][$columnN + 2] ?? null)) {
                        return [$columnN + 2, $rowN];
                    }
                }
                if (1 === $game->countHorizontalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN][$columnN + 1] ?? null) && $player === ($field[$rowN][$columnN + 2] ?? null)) {
                        return [$columnN + 1, $rowN];
                    }
                }
                if (2 === $game->countVerticalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN - 1][$columnN] ?? null)) {
                        return [$columnN, $rowN - 1];
                    }
                    if (' ' === ($field[$rowN + 2][$columnN] ?? null)) {
                        return [$columnN, $rowN + 2];
                    }
                }
                if (1 === $game->countVerticalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN + 1][$columnN] ?? null) && $player === ($field[$rowN + 2][$columnN] ?? null)) {
                        return [$columnN, $rowN + 1];
                    }
                }
                if (2 === $game->countLeftDownDiagonalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN + 2][$columnN - 2] ?? null)) {
                        return [$columnN - 2, $rowN + 2];
                    }
                    if (' ' === ($field[$rowN - 1][$columnN + 1] ?? null)) {
                        return [$columnN + 1, $rowN - 1];
                    }
                }
                if (1 === $game->countLeftDownDiagonalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN + 1][$columnN - 1] ?? null) && $player === ($field[$rowN + 2][$columnN - 2] ?? null)) {
                        return [$columnN - 1, $rowN + 1];
                    }
                }
                if (2 === $game->countRightDownDiagonalPlayerCells($player, [$columnN, $rowN])) {
                    if (' ' === ($field[$rowN + 2][$columnN + 2] ?? null)) {
                        return [$columnN + 2, $rowN + 2];
                    }
                    if (' ' === ($field[$rowN - 1][$columnN - 1] ?? null)) {
                        return [$columnN - 1, $rowN - 1];
                    }
                }
                if (1 === $game->countRightDownDiagonalPlayerCells($player, [$columnN, $rowN])) {
                    if ($player === ($field[$rowN + 2][$columnN + 2] ?? null) && ' ' === ($field[$rowN + 1][$columnN + 1] ?? null)) {
                        return [$columnN + 1, $rowN + 1];
                    }
                }
            }
        }

        return null;
    }

    private function getRandTurn(Game $game): ?array
    {
        $availableCoords = $game->getEmptyCells();

        $i = rand(0, count($availableCoords) - 1);

        return $availableCoords[$i] ?? null;
    }
}
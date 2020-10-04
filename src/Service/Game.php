<?php

declare(strict_types=1);

namespace App\Service;

class Game
{
    private array $field;

    public function __construct(array $field)
    {

        $this->field = $field;
    }

    public function getField(): array
    {
        return $this->field;
    }

    public function go(string $player, array $coords)
    {
        $this->field[$coords[1]][$coords[0]] = $player;
    }

    public function isOver(): bool
    {
        return $this->hasWinner() || !$this->hasEmptyCell();
    }

    private function hasEmptyCell(): bool
    {
        return count($this->getEmptyCells()) > 0;
    }

    public function getEmptyCells(): array
    {
        $cells = [];
        foreach ($this->getField() as $rowN => $row) {
            foreach ($row as $columnN => $value) {
                if ($value === ' ') {
                    $cells[] = [$columnN, $rowN];
                }
            }
        }

        return $cells;
    }

    public function getState(): string
    {
        if (!$this->hasEmptyCell()) {
            return 'Закончились ходы';
        }
        if ($this->hasWinner()) {
            return sprintf('Победил игрок "%s"', $this->getWinner());
        }

        return 'Игра в разгаре';
    }

    private function hasWinner(): bool
    {
        return $this->getWinner() ? true : false;
    }

    private function getWinner(): ?string
    {
        foreach (['x', 'o'] as $player) {
            $res = $this->playerHasLine($player);
            if ($res) {
                return $player;
            }
        }

        return null;
    }

    public function playerHasLine(string $player): bool
    {
        foreach ($this->getField() as $rowN => $row) {
            foreach ($row as $columnN => $value) {
                if (3 === $this->countHorizontalPlayerCells($player, [$columnN, $rowN])) {
                    return true;
                }
                if (3 === $this->countVerticalPlayerCells($player, [$columnN, $rowN])) {
                    return true;
                }
                if (3 === $this->countLeftDownDiagonalPlayerCells($player, [$columnN, $rowN])) {
                    return true;
                }
                if (3 === $this->countRightDownDiagonalPlayerCells($player, [$columnN, $rowN])) {
                    return true;
                }
            }
        }

        return false;
    }

    public function countHorizontalPlayerCells(string $player, array $startCell): int
    {
        $value = $this->field[$startCell[1]][$startCell[0]] ?? null;
        if ($value !== $player) {
            return 0;
        }

        return 1 + $this->countHorizontalPlayerCells($player, [$startCell[0] + 1, $startCell[1]]);
    }

    public function countVerticalPlayerCells(string $player, array $startCell): int
    {
        $value = $this->field[$startCell[1]][$startCell[0]] ?? null;
        if ($value !== $player) {
            return 0;
        }

        return 1 + $this->countVerticalPlayerCells($player, [$startCell[0], $startCell[1] + 1]);
    }

    public function countLeftDownDiagonalPlayerCells(string $player, array $startCell): int
    {
        $value = $this->field[$startCell[1]][$startCell[0]] ?? null;
        if ($value !== $player) {
            return 0;
        }

        return 1 + $this->countLeftDownDiagonalPlayerCells($player, [$startCell[0] - 1, $startCell[1] + 1]);
    }

    public function countRightDownDiagonalPlayerCells(string $player, array $startCell): int
    {
        $value = $this->field[$startCell[1]][$startCell[0]] ?? null;
        if ($value !== $player) {
            return 0;
        }

        return 1 + $this->countRightDownDiagonalPlayerCells($player, [$startCell[0] + 1, $startCell[1] + 1]);
    }
}

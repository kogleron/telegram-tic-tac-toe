<?php

declare(strict_types=1);

namespace App\Service;

class Game
{
    private array $field;

    public function __construct(array $field){

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

    private function playerHasLine(string $player): bool
    {
        foreach ($this->getField() as $rowN => $row) {
            foreach ($row as $columnN => $value) {
                if ($value !== $player) {
                    continue;
                }
            }
        }

        return false;
    }
}

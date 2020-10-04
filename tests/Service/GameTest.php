<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Game;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\Game
 */
class GameTest extends TestCase
{
    /**
     * @covers ::playerHasLine
     * @@dataProvider dataPlayerHasLine
     */
    public function testPlayerHasLine(array $field, $expected)
    {
        $game = new Game($field);
        self::assertEquals($expected,$game->playerHasLine('x'));
    }

    public function dataPlayerHasLine()
    {
        return [
            [
                'field' => [
                    ['x', 'x', 'x'],
                    [' ', ' ', ' '],
                    [' ', ' ', ' '],
                ],
                true
            ],
            [
                'field' => [
                    [' ', ' ', ' '],
                    ['x', 'x', 'x'],
                    [' ', ' ', ' '],
                ],
                true
            ],
            [
                'field' => [
                    [' ', ' ', ' '],
                    [' ', ' ', ' '],
                    ['x', 'x', 'x'],
                ],
                true
            ],
            [
                'field' => [
                    ['x', 'x', 'o'],
                    [' ', ' ', ' '],
                    [' ', ' ', ' '],
                ],
                false
            ],
            [
                'field' => [
                    ['x', 'x', 'o'],
                    ['x', ' ', ' '],
                    ['x', ' ', ' '],
                ],
                true
            ],
            [
                'field' => [
                    ['x', 'x', 'o'],
                    ['x', 'x', ' '],
                    [' ', 'x', ' '],
                ],
                true
            ],
            [
                'field' => [
                    ['x', 'o', 'x'],
                    [' ', ' ', 'x'],
                    ['x', ' ', 'x'],
                ],
                true
            ],
            [
                'field' => [
                    ['x', 'o', 'x'],
                    [' ', 'x', ' '],
                    ['x', ' ', 'x'],
                ],
                true
            ],
        ];
    }
}

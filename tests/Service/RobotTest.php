<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\Game;
use App\Service\Robot;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \App\Service\Robot
 */
class RobotTest extends TestCase
{
    /**
     * @covers ::predictPlayerTurn
     * @dataProvider dataGetNextCoords
     */
    public function testGetNextCoords(array $field, array $expected)
    {
        $game = new Game($field);
        $robot = new Robot('o');

        self::assertEquals($expected, $robot->predictPlayerTurn('o', $game));
    }

    public function dataGetNextCoords()
    {
        return [
            [
                [
                    ['o', 'o', ' '],
                    ['x', ' ', ' '],
                    ['x', ' ', ' '],
                ],
                [2, 0],
            ],
            [
                [
                    ['x', ' ', ' '],
                    [' ', 'o', 'o'],
                    ['x', ' ', ' '],
                ],
                [0, 1],
            ],
            [
                [
                    ['o', ' ', ' '],
                    ['x', 'x', ' '],
                    ['o', ' ', 'o'],
                ],
                [1, 2],
            ],
            [
                [
                    ['x', 'o', ' '],
                    [' ', 'o', 'x'],
                    ['x', ' ', ' '],
                ],
                [1, 2],
            ],
            [
                [
                    ['x', 'o', 'o'],
                    [' ', 'x', ' '],
                    ['x', 'o', 'o'],
                ],
                [2, 1],
            ],
            [
                [
                    [' ', 'o', 'o'],
                    ['o', 'x', ' '],
                    ['o', 'o', ' '],
                ],
                [0, 0],
            ],
            [
                [
                    [' ', 'o', ' '],
                    ['x', 'o', ' '],
                    ['o', 'x', ' '],
                ],
                [2, 0],
            ],
            [
                [
                    [' ', ' ', 'o'],
                    ['x', 'o', ' '],
                    [' ', 'x', ' '],
                ],
                [0, 2],
            ],
            [
                [
                    [' ', ' ', 'o'],
                    ['x', ' ', ' '],
                    ['o', 'x', ' '],
                ],
                [1, 1],
            ],
            [
                [
                    ['o', ' ', 'x'],
                    ['x', 'o', ' '],
                    ['o', 'x', ' '],
                ],
                [2, 2],
            ],
            [
                [
                    [' ', ' ', 'x'],
                    ['x', 'o', ' '],
                    ['o', 'x', 'o'],
                ],
                [0, 0],
            ],
            [
                [
                    ['o', ' ', 'x'],
                    ['x', ' ', ' '],
                    ['o', 'x', 'o'],
                ],
                [1, 1],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Telegram\Command;

use App\Service\Game;
use App\Service\GameManager;
use App\Service\Printer;
use App\Service\Robot;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use RuntimeException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use Throwable;

class GoCommand extends AbstractCommand implements PublicCommandInterface
{
    use FieldTrait;

    private Printer $printer;
    private Robot $robot;
    private GameManager $gameManager;

    public function __construct(Printer $printer, Robot $robot, GameManager $gameManager)
    {
        $this->printer = $printer;
        $this->robot = $robot;
        $this->gameManager = $gameManager;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return '/go';
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Update $update)
    {
        if (!$this->checkGameExistence($update, $api)) {
            return null;
        }

        $game = $this->gameManager->getGame($update);

        if ($game->isOver()) {
            return $this->printResult($api, $update, $game);
        }

        try {
            $this->playerTurn($update, $game);
        } catch (Throwable $throwable) {
            $api->sendMessage(
                $update->getMessage()->getChat()->getId(),
                $throwable->getMessage(),
                'HTML'
            );

            return null;
        }
        $this->gameManager->saveGame($update, $game);

        if ($game->isOver()) {
            return $this->printResult($api, $update, $game);
        }

        $this->computerTurn($game);
        $this->gameManager->saveGame($update, $game);

        if ($game->isOver()) {
            return $this->printResult($api, $update, $game);
        }

        return $this->printField($api, $update, $game);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Сделать ход. Например /go a1';
    }

    private function parseCoordinates(string $text): array
    {
        $map = ['a' => 0, 'b' => 1, 'c' => 2];

        $strCoords = preg_replace('/\/go\s+/', '', $text);
        $strCoords = strtolower($strCoords);
        $coords = str_split($strCoords, 1);
        if (!$this->validateCoords($coords)) {
            throw new RuntimeException('Неправильные координаты');
        }

        $coords[0] = $map[$coords[0]];
        $coords[1] = intval($coords[1]) - 1;

        return $coords;
    }

    private function computerTurn(Game $game)
    {
        $robotCoords = $this->robot->getNextCoords($game);
        if (!$robotCoords) {
            return;
        }

        $game->go('o', $robotCoords);
    }

    private function printResult(BotApi $api, Update $update, Game $game)
    {
        $this->printField($api, $update, $game);
        $api->sendMessage(
            $update->getMessage()->getChat()->getId(),
            $game->getState(),
            'HTML'
        );
    }

    private function playerTurn(Update $update, Game $game): void
    {
        $coords = $this->parseCoordinates($update->getMessage()->getText());
        if (' ' !== $game->getField()[$coords[1]][$coords[0]]) {
            throw new RuntimeException('Ячейка уже занята');
        }

        $game->go('x', $coords);
    }

    private function validateCoords(array $coords): bool
    {
        return
            preg_match('/^[abc]$/', $coords[0]) !== false
            &&
            preg_match('/^[123]$/', $coords[1]) !== false;
    }
}

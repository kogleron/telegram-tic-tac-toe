<?php

declare(strict_types=1);

namespace App\Telegram\Command;

use App\Service\Game;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

trait FieldTrait
{
    protected function printField(BotApi $api, Update $update, Game $game)
    {
        $api->sendMessage(
            $update->getMessage()->getChat()->getId(),
            $this->printer->print($game->getField()),
            'HTML'
        );
    }

    private function checkGameExistence(Update $update, BotApi $api): bool
    {
        if ($this->gameManager->hasGame($update)) {
            return true;
        }

        $api->sendMessage(
            $update->getMessage()->getChat()->getId(),
            'Нет активной игры',
            'HTML'
        );

        return false;
    }
}
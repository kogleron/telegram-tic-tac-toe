<?php

declare(strict_types=1);

namespace App\Telegram\Command;

use App\Service\GameManager;
use App\Service\Printer;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class NowCommand extends AbstractCommand implements PublicCommandInterface
{
    use FieldTrait;

    private Printer $printer;
    private GameManager $gameManager;

    public function __construct(Printer $printer, GameManager $gameManager)
    {
        $this->printer = $printer;
        $this->gameManager = $gameManager;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return '/now';
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
        $this->printField($api, $update, $game);
        $api->sendMessage(
            $update->getMessage()->getChat()->getId(),
            $game->getState(),
            'HTML'
        );
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Показать текущее состояние игры';
    }
}

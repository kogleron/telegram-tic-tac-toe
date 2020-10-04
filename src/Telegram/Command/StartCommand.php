<?php

declare(strict_types=1);


namespace App\Telegram\Command;


use App\Service\GameManager;
use App\Service\Printer;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class StartCommand extends AbstractCommand implements PublicCommandInterface
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
        return '/start';
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Update $update)
    {
        $game = $this->gameManager->createGame();
        $this->gameManager->saveGame($update, $game);
        $this->printField($api, $update, $game);
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Начать игру';
    }
}
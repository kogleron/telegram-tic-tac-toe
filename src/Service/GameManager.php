<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use TelegramBot\Api\Types\Update;

class GameManager
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function hasGame(Update $update): bool
    {
        $field = $this->cache->get(
            $this->getKey($update),
            function () {
                return null;
            }
        );

        return !empty($field);
    }

    public function getGame(Update $update): Game
    {
        $field = $this->cache->get(
            $this->getKey($update),
            function () {
                return null;
            }
        );

        return new Game($field);
    }

    public function createGame(): Game
    {
        $field = [
            [' ', ' ', ' '],
            [' ', ' ', ' '],
            [' ', ' ', ' '],
        ];

        return new Game($field);
    }

    public function saveGame(Update $update, Game $game)
    {
        $this->cache->delete($this->getKey($update));
        $this->cache->get(
            $this->getKey($update),
            function (CacheItemInterface $cacheItem) use ($game) {
                $cacheItem->expiresAfter(3600);

                return $game->getField();
            }
        );
    }

    private function getKey(Update $update): string
    {
        return 'field' . $update->getMessage()->getChat()->getId();
    }
}

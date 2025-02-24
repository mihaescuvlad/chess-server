<?php

namespace ChessServer\Command;

use ChessServer\Socket;
use ChessServer\GameMode\PlayMode;
use ChessServer\Exception\InternalErrorException;
use Ratchet\ConnectionInterface;

class ResignCommand extends AbstractCommand
{
    const ACTION_ACCEPT    = 'accept';

    public function __construct()
    {
        $this->name = '/resign';
        $this->description = 'Allows to resign a game.';
        $this->params = [
            // mandatory param
            'action' => [
                self::ACTION_ACCEPT,
            ],
        ];
    }

    public function validate(array $argv)
    {
        if (in_array($argv[1], $this->params['action'])) {
            return count($argv) - 1 === count($this->params);
        }

        return false;
    }

    public function run(Socket $socket, array $argv, ConnectionInterface $from)
    {
        $gameMode = $socket->getGameModeStorage()->getByResourceId($from->resourceId);

        if (!$gameMode) {
            throw new InternalErrorException();
        }

        if (is_a($gameMode, PlayMode::class)) {
            return $socket->sendToMany(
                $gameMode->getResourceIds(),
                $gameMode->res($argv, $this)
            );
        }
    }
}

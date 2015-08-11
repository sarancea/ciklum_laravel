<?php

namespace Participants;

require_once 'AbstractParticipant.php';
require_once 'Computer.php';

class Player extends AbstractParticipant
{

    /**
     * Loads a game from existing data
     *
     * @param array $gameData
     */
    public function loadExistingGame($gameData)
    {
        $this->currentGame = \TicTacToeGame::loadFromArray($gameData);
    }


    /**
     * Create a new game
     */
    public function createNewGame()
    {
        $this->currentGame = new \TicTacToeGame();
        $this->currentGame->setOpponent(new Computer(time() . '_computer'));
        $this->currentGame->setPlayerId($this->id);
        $this->currentGame->getOpponent();

    }
}
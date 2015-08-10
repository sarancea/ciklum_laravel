<?php

namespace Participants;

abstract class AbstractParticipant
{

    protected $id;

    /**
     * @var \TicTacToeGame
     */
    protected $currentGame;


    public function __construct($id)
    {

    }

    /**
     * @return \TicTacToeGame
     */
    public function getCurrentGame()
    {
        return $this->currentGame;
    }

    /**
     * @param \TicTacToeGame $currentGame
     */
    protected function setCurrentGame($currentGame)
    {
        $this->currentGame = $currentGame;
    }

}
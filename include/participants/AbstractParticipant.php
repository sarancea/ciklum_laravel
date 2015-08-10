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
        $this->id = $id;
    }

    /**
     * @return \TicTacToeGame
     */
    public function getCurrentGame()
    {
        return $this->currentGame;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


}
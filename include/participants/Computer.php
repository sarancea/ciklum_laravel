<?php

namespace Participants;

class Computer extends AbstractParticipant
{

    /**
     * Setting current game for Computer Player
     * @param \TicTacToeGame $game
     */
    public function setCurrentGame(\TicTacToeGame $game)
    {
        $this->currentGame = $game;
    }


    public function generateMove()
    {
        $currentMatrix = $this->currentGame->getCurrentMatrix();

        $amIWining = null;
        $amILoosing = null;


        for ($y = 0; $y < $this->currentGame->getYSize(); $y++) {
            for ($x = 0; $x < $this->currentGame->getXSize(); $x++) {

            }
        }

        return array('x' => 0, 'y' => 0);
    }

    /**
     *
     * @param bool $amIWining
     * @param bool $amILoosing
     */
    protected function decisionMaker($amIWining, $amILoosing)
    {

    }
}
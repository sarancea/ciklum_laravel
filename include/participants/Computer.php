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


    /**
     * Returns the next move
     *
     * @return array
     */
    public function generateMove()
    {
        $movesList = array();
        $this->calculatePossibleMoves($movesList);

        // get the best move
        usort($movesList, function ($a, $b) {
            if ($a['probabilityOfSuccess'] == $b['probabilityOfSuccess']) {
                return 0;
            }
            return ($a['probabilityOfSuccess'] < $b['probabilityOfSuccess']) ? 1 : -1;
        });


        return $movesList[0];
    }

    /**
     * Generates a list of possible moves
     */
    protected function calculatePossibleMoves(array &$moves)
    {

        $currentMatrix = $this->currentGame->getCurrentMatrix();

        for ($y = 0; $y < $this->currentGame->getYSize(); $y++) {

            for ($x = 0; $x < $this->currentGame->getXSize(); $x++) {

                if (is_null($currentMatrix[$y][$x])) {
                    $moves[] = array(
                        'y' => $y,
                        'x' => $x,
                        'probabilityOfSuccess' => $this->calculateProbabilityOfSuccess($y, $x),
                    );
                }

            }

        }
    }


    /**
     * Calculate the probability of success if we mark specified cell
     *
     * @param int $y
     * @param int $x
     * @return int
     */
    protected function calculateProbabilityOfSuccess($y, $x)
    {
        $currentMatrix = $this->getCurrentGame()->getCurrentMatrix();

        //check if this is a won strategy
        if ($this->isWonStrategy($y, $x)) {
            return 100;
        }

        //check if this is a stop-won strategy
        if ($this->isStopLossStrategy($y, $x)) {
            return 90;
        }

        //check if this is a stop-won strategy
        if ($this->isMiddleStrategy($y, $x)) {
            return 80;
        }

        //check if this is a stop-won strategy
        if ($this->isAngleStrategy($y, $x)) {
            return 70;
        }

        return 20;
    }

    /**
     * Check if the cell is middle one
     *
     * @param int $y
     * @param int $x
     * @return bool
     */
    protected function isMiddleStrategy($y, $x)
    {
        if (0 == (($this->getCurrentGame()->getYSize() - 1) % 2) &&
            $y == $x && $y != 0 && $y != ($this->getCurrentGame()->getYSize() - 1)
        ) {
            return true;
        } elseif (($this->getCurrentGame()->getYSize() - 1) > 2) {
            $range = [ceil(($this->getCurrentGame()->getYSize() - 1) / 2), floor(($this->getCurrentGame()->getYSize() - 1) / 2)];
            if (in_array($y, $range) && in_array($x, $range)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if t
     *
     * @param int $y
     * @param int $x
     * @return bool
     */
    protected function isAngleStrategy($y, $x)
    {
        if ($x == 0 || $x == ($this->getCurrentGame()->getXSize() - 1)) {
            if ($y == 0 || $y == ($this->getCurrentGame()->getYSize() - 1)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Check if we win in case we move here
     *
     * @param int $y
     * @param int $x
     *
     * @return bool
     */
    protected function isWonStrategy($y, $x)
    {
        return $this->isWonStrategyForId($y, $x, $this->getId());
    }


    /**
     * Check if we stop the wining of player in case we move here
     *
     * @param int $y
     * @param int $x
     *
     * @return bool
     */
    protected function isStopLossStrategy($y, $x)
    {
        return $this->isWonStrategyForId($y, $x, $this->getCurrentGame()->getPlayerId());
    }

    /**
     * @param int $y
     * @param int $x
     * @param string $id
     * @return bool
     */
    protected function isWonStrategyForId($y, $x, $id)
    {
        $currentMatrix = $this->getCurrentGame()->getCurrentMatrix();

        //check straight line for x axis
        $numberOfMyCells = 0;
        for ($i = 0; $i < $this->getCurrentGame()->getYSize(); $i++) {
            if (!is_null($currentMatrix[$i][$x]) && $currentMatrix[$i][$x] === $id) {
                $numberOfMyCells++;
            }
        }

        if ($numberOfMyCells == ($this->getCurrentGame()->getYSize() - 1)) {
            return true;
        }


        //check straight line for y axis
        $numberOfMyCells = 0;
        for ($i = 0; $i < $this->getCurrentGame()->getXSize(); $i++) {
            if (!is_null($currentMatrix[$y][$i]) && $currentMatrix[$y][$i] === $id) {
                $numberOfMyCells++;
            }
        }

        if ($numberOfMyCells == ($this->getCurrentGame()->getYSize() - 1)) {
            return true;
        }

        $numberOfMyCells = 0;

        //check the diagonals
        //This algorithm is applicable for 3x3 matrix
        //@Todo change the algorithm for any matrix
        //Diagonals could be build only for extreme points

        if ($this->getCurrentGame()->getXSize() == 3) {

            //No diagonals possible
            if ($x != 0 && $x != $this->getCurrentGame()->getXSize() && $x != $y) {
                if ($y != 0 && $y != $this->getCurrentGame()->getYSize()) {
                    return false;
                }
            }

            for ($i = 1; $i < $this->getCurrentGame()->getXSize(); $i++) {

                $dX = ($x == 0 ? ($x + $i) : ($x - $i));
                $dY = ($y == 0 ? ($y + $i) : ($y - $i));

                if (
                    isset($currentMatrix[$dY][$dX])
                    && !is_null($currentMatrix[$dY][$dX])
                    && $currentMatrix[$dY][$dX] === $id
                ) {
                    $numberOfMyCells++;
                }

            }


            if ($numberOfMyCells == ($this->getCurrentGame()->getYSize() - 1)) {
                return true;
            }
        }

        return false;
    }
}
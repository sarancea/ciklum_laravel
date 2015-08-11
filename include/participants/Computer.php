<?php

namespace Participants;

class Computer extends AbstractParticipant
{


    /**
     * Returns the next move
     *
     * @param $currentGame
     * @return array
     */
    public function generateMove($currentGame)
    {
        $movesList = array();
        $this->calculatePossibleMoves($currentGame, $movesList);

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
    protected function calculatePossibleMoves(\TicTacToeGame $currentGame, array &$moves)
    {

        $currentMatrix = $currentGame->getCurrentMatrix();

        for ($y = 0; $y < $currentGame->getYSize(); $y++) {

            for ($x = 0; $x < $currentGame->getXSize(); $x++) {

                if (is_null($currentMatrix[$y][$x])) {
                    $moves[] = array(
                        'y' => $y,
                        'x' => $x,
                        'probabilityOfSuccess' => $this->calculateProbabilityOfSuccess($y, $x, $currentGame),
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
     * @param \TicTacToeGame $currentGame
     * @return int
     */
    protected function calculateProbabilityOfSuccess($y, $x, \TicTacToeGame $currentGame)
    {

        //check if this is a won strategy
        if ($this->isWonStrategy($y, $x, $currentGame)) {
            return 100;
        }

        //check if this is a stop-won strategy
        if ($this->isStopLossStrategy($y, $x, $currentGame)) {
            return 90;
        }

        //check if this is a stop-won strategy
        if ($this->isMiddleStrategy($y, $x, $currentGame)) {
            return 80;
        }

        //check if this is a stop-won strategy
        if ($this->isAngleStrategy($y, $x, $currentGame)) {
            return 70;
        }

        return 20;
    }

    /**
     * Check if the cell is middle one
     *
     * @param int $y
     * @param int $x
     * @param \TicTacToeGame $currentGame
     * @return bool
     */
    protected function isMiddleStrategy($y, $x, \TicTacToeGame $currentGame)
    {
        if (0 == (($currentGame->getYSize() - 1) % 2) &&
            $y == $x && $y != 0 && $y != ($currentGame->getYSize() - 1)
        ) {
            return true;
        } elseif (($currentGame->getYSize() - 1) > 2) {
            $range = [ceil(($currentGame->getYSize() - 1) / 2), floor(($currentGame->getYSize() - 1) / 2)];
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
     * @param \TicTacToeGame $currentGame
     * @return bool
     */
    protected function isAngleStrategy($y, $x, \TicTacToeGame $currentGame)
    {
        if ($x == 0 || $x == ($currentGame->getXSize() - 1)) {
            if ($y == 0 || $y == ($currentGame->getYSize() - 1)) {
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
     * @param \TicTacToeGame $currentGame
     * @return bool
     */
    protected function isWonStrategy($y, $x, \TicTacToeGame $currentGame)
    {
        return $this->isWonStrategyForId($y, $x, $this->getId(), $currentGame);
    }


    /**
     * Check if we stop the wining of player in case we move here
     *
     * @param int $y
     * @param int $x
     *
     * @param \TicTacToeGame $currentGame
     * @return bool
     */
    protected function isStopLossStrategy($y, $x, \TicTacToeGame $currentGame)
    {
        return $this->isWonStrategyForId($y, $x, $currentGame->getPlayerId(), $currentGame);
    }

    /**
     * @param int $y
     * @param int $x
     * @param string $id
     * @param \TicTacToeGame $currentGame
     * @return bool
     */
    protected function isWonStrategyForId($y, $x, $id, \TicTacToeGame $currentGame)
    {
        $currentMatrix = $currentGame->getCurrentMatrix();

        //check straight line for x axis
        $numberOfMyCells = 0;
        for ($i = 0; $i < $currentGame->getYSize(); $i++) {
            if (!is_null($currentMatrix[$i][$x]) && $currentMatrix[$i][$x] === $id) {
                $numberOfMyCells++;
            }
        }

        if ($numberOfMyCells == ($currentGame->getYSize() - 1)) {
            return true;
        }


        //check straight line for y axis
        $numberOfMyCells = 0;
        for ($i = 0; $i < $currentGame->getXSize(); $i++) {
            if (!is_null($currentMatrix[$y][$i]) && $currentMatrix[$y][$i] === $id) {
                $numberOfMyCells++;
            }
        }

        if ($numberOfMyCells == ($currentGame->getYSize() - 1)) {
            return true;
        }

        $numberOfMyCells = 0;

        //check the diagonals
        //This algorithm is applicable for 3x3 matrix
        //@Todo change the algorithm for any matrix
        //Diagonals could be build only for extreme points

        if ($currentGame->getXSize() == 3) {

            //No diagonals possible
            if ($x != 0 && $x != $currentGame->getXSize() && $x != $y) {
                if ($y != 0 && $y != $currentGame->getYSize()) {
                    return false;
                }
            }

            for ($i = 1; $i < $currentGame->getXSize(); $i++) {

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


            if ($numberOfMyCells == ($currentGame->getYSize() - 1)) {
                return true;
            }
        }

        return false;
    }
}
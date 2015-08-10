<?php


use Participants\AbstractParticipant;

class TicTacToeGame
{

    /**
     * @var int
     */
    protected $xSize;

    /**
     * @var int
     */
    protected $ySize;

    /**
     * @var array
     */
    protected $currentMatrix;

    /**
     * @var array
     */
    protected $participantsList;

    /**
     * @var AbstractParticipant
     */
    protected $servingNow;


    public function __construct($ySize = 3, $xSize = 3)
    {
        $this->currentMatrix = $this->createCleanMatrix($ySize, $xSize);
    }



    /**
     * Return the current state of matrix
     * @return array
     */
    public function getCurrentMatrix()
    {
        return $this->currentMatrix;
    }


    /**
     * Creates a matrix of specified size with null values
     *
     * @param int $xSize
     * @param int $ySize
     * @return array
     */
    protected function createCleanMatrix($ySize, $xSize)
    {
        $matrix = [];
        for ($y = 0; $y < $ySize; $y++) {
            $matrix[$y] = [];
            for ($x = 0; $x < $xSize; $x++) {
                $matrix[$y][$x] = null;
            }
        }

        return $matrix;
    }

}
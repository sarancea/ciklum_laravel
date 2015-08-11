<?php


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
     * @var string
     */
    protected $playerId;

    /**
     * @var \Participants\Computer
     */
    protected $opponent;

    /**
     * @var bool|string
     */
    protected $isFinished = false;


    public function __construct()
    {
        $size = 3;
        $this->xSize = $size;
        $this->ySize = $size;
        $this->currentMatrix = $this->createCleanMatrix($this->ySize, $this->xSize);
    }


    /**
     * Maps data from array to an object
     * @param array $gameData
     * @return \TicTacToeGame
     * @throws RuntimeException
     */
    public static function loadFromArray(array $gameData)
    {
        if (!array_key_exists('xSize', $gameData)) {
            throw new RuntimeException('xSize field is missing');
        }

        if (!array_key_exists('ySize', $gameData)) {
            throw new RuntimeException('ySize field is missing');
        }

        if (!array_key_exists('playerId', $gameData)) {
            throw new RuntimeException('playerId field is missing');
        }

        if (!array_key_exists('currentMatrix', $gameData)) {
            throw new RuntimeException('currentMatrix field is missing');
        }

        if (!array_key_exists('opponentId', $gameData)) {
            throw new RuntimeException('opponentId field is missing');
        }

        $gameInstance = new self;

        $gameInstance->xSize = $gameData['xSize'];
        $gameInstance->ySize = $gameData['ySize'];
        $gameInstance->currentMatrix = $gameData['currentMatrix'];
        $gameInstance->playerId = $gameData['playerId'];
        $gameInstance->opponent = new \Participants\Computer($gameData['opponentId']);

        return $gameInstance;
    }


    /**
     * Add a point
     *
     * @param int $x
     * @param int $y
     * @throws RuntimeException
     */
    public function markPoint($x, $y)
    {

        if (false !== $this->isFinished()) {
            throw new RuntimeException('Game is finished');
        }

        if (!is_null($this->currentMatrix[$y][$x])) {
            throw new RuntimeException('Cell already marked');
        }

        $this->currentMatrix[$y][$x] = $this->getPlayerId();

        $this->gameIsFinishedCheck($this->getPlayerId());

        if (false === $this->isFinished()) {

            $point = $this->getOpponent()->generateMove($this);


            $this->currentMatrix[$point['y']][$point['x']] = $this->getOpponent()->getId();

            $this->gameIsFinishedCheck($this->getOpponent()->getId());
        }
    }


    /**
     * Check if game is finished after move
     */
    protected function gameIsFinishedCheck($id)
    {
        //Check straight lines

        for ($y = 0; $y < $this->getYSize(); $y++) {

            $resultX = 0;
            $resultY = 0;

            for ($x = 0; $x < $this->getXSize(); $x++) {

                if ($this->currentMatrix[$y][$x] === $id) {
                    $resultX++;
                }

                if ($this->currentMatrix[$x][$y] === $id) {
                    $resultY++;
                }
            }

            if ($resultY == $this->getXSize() || $resultX == $this->getXSize()) {
                $this->isFinished($id);
                return;
            }

        }


        //check diagonals
        $result = 0;
        for ($y = 0; $y < $this->getYSize(); $y++) {
            $x = $y;
            if (!is_null($this->currentMatrix[$y][$x]) && $this->currentMatrix[$y][$x] === $id) {
                $result++;
            }
        }


        if ($result == $this->getXSize()) {
            $this->isFinished($id);
            return;
        }

        $result = 0;
        for ($y = 0; $y < $this->getYSize(); $y++) {
            $x = ($this->getYSize() - 1) - $y;
            if (!is_null($this->currentMatrix[$y][$x]) && $this->currentMatrix[$y][$x] === $id) {
                $result++;
            }
        }

        if ($result == $this->getXSize()) {
            $this->isFinished($id);
            return;
        }

        //check for tie game
        $result = 0;
        for ($y = 0; $y < $this->getYSize(); $y++) {
            for ($x = ($this->getXSize() - 1); $x >= 0; $x--) {
                if (!is_null($this->currentMatrix[$y][$x])) {
                    $result++;
                }
            }

            if ($result == ($this->getXSize() * $this->getXSize())) {
                $this->isFinished('tie');
                return;
            }
        }

    }


    /**
     * Convert object to an array data
     * @return array
     */
    public function toArray()
    {
        return array(
            'xSize' => $this->xSize,
            'ySize' => $this->ySize,
            'currentMatrix' => $this->currentMatrix,
            'playerId' => $this->playerId,
            'opponentId' => $this->opponent->getId(),
            'isFinished' => $this->isFinished(),
        );
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
        $matrix = array();
        for ($y = 0; $y < $ySize; $y++) {
            $matrix[$y] = array();
            for ($x = 0; $x < $xSize; $x++) {
                $matrix[$y][$x] = null;
            }
        }

        return $matrix;
    }

    /**
     * @param \Participants\AbstractParticipant $opponent
     */
    public function setOpponent($opponent)
    {
        $this->opponent = $opponent;
    }

    /**
     * @return \Participants\Computer
     */
    public function getOpponent()
    {
        return $this->opponent;
    }

    /**
     * @return string
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @param string $servingNow
     */
    public function setPlayerId($servingNow)
    {
        $this->playerId = $servingNow;
    }

    /**
     * @param array $currentMatrix
     */
    public function setCurrentMatrix($currentMatrix)
    {
        $this->currentMatrix = $currentMatrix;
    }

    /**
     * @return int
     */
    public function getYSize()
    {
        return $this->ySize;
    }

    /**
     * @return int
     */
    public function getXSize()
    {
        return $this->xSize;
    }

    /**
     * @param string|null $isFinished
     * @return boolean|string
     */
    public function isFinished($isFinished = null)
    {
        if (!is_null($isFinished)) {
            $this->isFinished = $isFinished;
        }

        return $this->isFinished;
    }

}
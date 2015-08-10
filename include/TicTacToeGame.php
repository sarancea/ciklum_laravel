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
     * @param int $ySize
     * @param int $xSize
     */
    public function __construct($ySize = 3, $xSize = 3)
    {
        $this->xSize = $xSize;
        $this->ySize = $ySize;
        $this->currentMatrix = $this->createCleanMatrix($ySize, $xSize);
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
        if (isset($this->currentMatrix[$y][$x])) {
            throw new RuntimeException('Position already used');
        }

        $this->currentMatrix[$y][$x] = $this->getPlayerId();

        $point = $this->getOpponent()->generateMove();

        $this->currentMatrix[$point['y']][$point['x']] = $this->getOpponent()->getId();
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

}
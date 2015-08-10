<?php

session_start();

require_once 'include/Participants/Player.php';
require_once 'include/TicTacToeGame.php';


if (!isset($_SESSION['playerId'])) {
    $_SESSION['playerId'] = time();
}

$player = new \Participants\Player($_SESSION['playerId']);

if (isset($_SESSION['currentGame'])) {
    try {
        $player->loadExistingGame($_SESSION['currentGame']);
    } catch (RuntimeException $e) {
        session_destroy();
    }
} else {
    $player->createNewGame();
}

if (count($_POST)) {
    if (!isset($_POST['x']) || !isset($_POST['y'])) {
        throw new RuntimeException('Point is missing');
    }

}

//Save current game to session
$_SESSION['currentGame'] = $player->getCurrentGame()->toArray();

echo json_encode($player->getCurrentGame()->toArray()) . PHP_EOL;
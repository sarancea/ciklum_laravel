<?php

session_start();

require_once 'include/Participants/Player.php';

if (!isset($_SESSION['playerId'])) {
    $_SESSION['playerId'] = hash_hmac('md5', time(), session_id());
}

$player = new \Participants\Player($_SESSION['playerId']);
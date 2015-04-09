<?php
include_once 'bd/bd.php';
include_once 'classes/bd.php';
include_once 'classes/Server.class.php';

/** Данные приложения  * */
$server = new Server($db);
$server->getPlayList($_GET['access_token'], $_GET['user_id']);
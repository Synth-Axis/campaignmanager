<?php

if (!defined('ENV')) {
    define('ENV', parse_ini_file(__DIR__ . '/../.env'));
}

require_once(__DIR__ . "/../models/users.php");
require_once(__DIR__ . "/../models/managers.php");
require_once(__DIR__ . "/../models/channels.php");
require_once(__DIR__ . "/../models/publico.php");

$modelUsers = new Users();
$modelManagers = new Managers();
$modelChannels = new Channels();
$modelPublico = new Publico();

$users = $modelUsers->getAllUsers();
$managers = $modelManagers->getAllManagers();
$channels = $modelChannels->getAllChannels();
$Publico = $modelPublico->getAllPublico();

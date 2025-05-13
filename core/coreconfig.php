<?php

require("models/users.php");
require("models/agents.php");
require("models/managers.php");
require("models/channels.php");
require("models/utilizadores.php");

$modelUsers = new Users();
$modelAgents = new Agents();
$modelManagers = new Managers();
$modelChannels = new Channels();
$modelUtilizadores = new Utilizadores();

$users = $modelUsers->getAllUsers();
$agents = $modelAgents->getAllAgents();
$managers = $modelManagers->getAllManagers();
$channels = $modelChannels->getAllChannels();
$utilizadores = $modelUtilizadores->getAllUtilizadores();

// if (empty($users)) {
//     http_response_code(404);
//     die("Not found");
// }

/*
if (isset($_SESSION["user_id"])){
    $currentUser = $modelUsers->findUserById($_SESSION["user_id"]);
    $ownedGamesCount = $modelOwnedGames->getGamesCount($currentUser["user_id"]);
}
*/
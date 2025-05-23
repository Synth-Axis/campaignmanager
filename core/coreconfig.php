<?php

require("models/users.php");
require("models/agents.php");
require("models/managers.php");
require("models/channels.php");
require("models/publico.php");

$modelUsers = new Users();
$modelAgents = new Agents();
$modelManagers = new Managers();
$modelChannels = new Channels();
$modelPublico = new Publico();

$users = $modelUsers->getAllUsers();
$agents = $modelAgents->getAllAgents();
$managers = $modelManagers->getAllManagers();
$channels = $modelChannels->getAllChannels();
$Publico = $modelPublico->getAllPublico();

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
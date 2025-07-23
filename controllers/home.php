<?php

require("models/users.php");
$model = new Users();

if (isset($_SESSION["user_id"])) {
    $currentUser = $model->findUserById($_SESSION["user_id"]);
}

require("views/home.php");

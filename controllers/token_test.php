<?php
require("models/users.php");
$model = new Users();

$model->storeRememberToken(1, hash('sha256', 'sample'), date("Y-m-d H:i:s", time() + 3600));
echo "done";
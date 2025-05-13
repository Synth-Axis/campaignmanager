<?php

function dd($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die;
}

function showMessage($message)
{
    if (isset($message)) {
        echo '<p role="alert" class="text-warning">' . $message . '</p>';
    }
}

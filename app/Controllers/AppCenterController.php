<?php

class AppCenterController
{
    public function index(): void
    {
        $currentUser = current_user();
        require view_path('app-center/index.php');
    }
}

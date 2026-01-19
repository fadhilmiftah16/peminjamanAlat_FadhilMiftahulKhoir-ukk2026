<?php
require_once '../app/config/config.php';
require_once '../app/config/database.php';
require_once '../app/core/controller.php';
require_once '../app/core/app.php';
require_once '../app/core/flasher.php';

session_start();

$app = new App();

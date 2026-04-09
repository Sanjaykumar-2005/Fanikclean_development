<?php
session_start();

require_once 'config/Database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';

// Instantiate Router
$router = new Router();
require_once 'routes/web.php';

// Dispatch
$url = $_GET['url'] ?? '/';
$router->dispatch($url);

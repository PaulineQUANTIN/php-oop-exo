<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

session_start();
session_unset();
session_destroy();

$url = 'login.php';
header("Location: {$url}", true, 302);
exit();


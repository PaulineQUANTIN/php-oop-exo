<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);

// chargement de l'extension Twig_Extension_Debug
$twig->addExtension(new \Twig\Extension\DebugExtension()); // à désactiver quand plus en phase de dev

session_start();

$user_id = [
    'login' => '',
    'user_id' => '',
];

if (isset($_SESSION['login'])) {
    $user_id['login'] = $_SESSION['login'];
} 

elseif (!isset($_SESSION['login'])) {
    $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit();
}


// affichage du rendu d'un template
echo $twig->render('private-page.html.twig', [
    // transmission de données au template
    'user_id' => $user_id,
]);
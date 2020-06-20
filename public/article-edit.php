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
$twig->addExtension(new \Twig\Extension\DebugExtension());

require __DIR__.'/articles-lib.php';

$article = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'quantity' => ''
];

if ($_GET) {
    if(isset($_GET['id'])) {
        $article['id'] = $_GET['id'];
        $article['name'] = $_GET['name'];
        $article['description'] = $_GET['description'];
        $article['price'] = $_GET['price'];
        $article['quantity'] = $_GET['quantity'];
    } 
    
    elseif (!isset($_GET['id']) || empty($_GET['id'])) {
        // aucun id n'est spécifié
        $url = 'article-404.php';
        header("Location: {$url}", true, 302);
        exit();
    }

    elseif (!articleExists($_GET['id'], $articles)) {
        // l'article n'existe pas
        $url = 'article-404.php';
        header("Location: {$url}", true, 302);
        exit();
    }
}

$data = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'quantity' => ''
];

$errors = [];
$messages = [];


if ($_POST) {
    if (isset($_POST['name'])) {
        $data['name'] = $_POST['name'];
    }

    if (isset($_POST['description'])) {
        if (
            strpos($_POST['description'], '<')
            || strpos($_POST['description'], '>')
        ) {
            $errors['description'] = true;
            $messages['description'] = "La description contient un caractère interdit < ou >";
        } else {
            $data['description'] = $_POST['description'];
        }
    }

    if (isset($_POST['price'])) {
        $data['price'] = $_POST['price'];
    }

    if (isset($_POST['quantity'])) {
        $data['quantity'] = $_POST['quantity'];
    }

    // validation des données envoyées par l'utiilisateur
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = true;
        $messages['name'] = "Merci de renseigner le nom de l'article";
    } elseif (strlen($_POST['name']) < 2) {
        $errors['name'] = true;
        $messages['name'] = "Le nom de l'article doit faire 2 caractères minimum";
    } elseif (strlen($_POST['name']) > 100) {
        $errors['name'] = true;
        $messages['name'] = "Le nom de l'article doit faire 100 caractères maximum";
    } 


    if (!isset($_POST['price']) || empty($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Merci de renseigner le prix de l'article";
    } elseif (!is_numeric($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = "Merci de renseigner un nombre";
    }


    if (!isset($_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Merci de renseigner une quantité";
    } elseif (!is_numeric($_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Merci de renseigner une quantité";
    } elseif (!is_int(0 + $_POST['quantity'])) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Merci de renseigner un nombre entier";
    } elseif (0 + $_POST['quantity'] < 0) {
        $errors['quantity'] = true;
        $messages['quantity'] = "Merci de renseigner un nombre supérieur ou égal à 0";
    }


    if (!$errors) {
        $url = 'articles.php';
        header("Location: {$url}", true, 302);
        exit();
    }
}

// affichage du rendu d'un template
echo $twig->render('article-edit.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'messages' => $messages,
    'get' => $_GET,
    'data' => $data,
    'article' => $article,
]);
<?php

require __DIR__.'/articles-lib.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    // aucun id n'est spécifié
    $url = 'article-404.php';
    header("Location: {$url}", true, 302);
    exit();
} elseif (!articleExists($_GET['id'], $articles)) {
    // l'article n'existe pas
    $url = 'articles.php';
    header("Location: {$url}", true, 302);
    exit();
}
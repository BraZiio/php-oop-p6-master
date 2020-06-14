<?php
$user = require __DIR__.'/user-data.php';

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

$formData = [
    'login' => '',
    'password'  => '',
];



if ($_POST) {
    $errors = [];

    // remplacement des valeur par défaut par celles de l'utilisateur
    if (isset($_POST['login'])) {
        $formData['login'] = $_POST['login'];
    }

    if (isset($_POST['password'])) {
        $formData['password'] = $_POST['password'];
    }

    // validation des données envoyées par l'utiilisateur
    if ($_POST['login'] == $user['login']) {

        if (!password_verify($_POST['password'], $user['password_hash'])) {
            echo 'mauvais mot passe';
             $url = 'login.php';
            header("Location: {$url}", true, 302);
            exit();
        }

        if (password_verify($_POST['password'], $user['password_hash'])) {
         $url = 'private-page.php';
        header("Location: {$url}", true, 302);
        exit(); 
        }
    } 

    if ($_POST['login'] != $user['login']) {
        echo 'mauvais login';
         $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit(); 
    }



    if (!isset($_POST['login']) || empty($_POST['login'])) {
        $errors['login'] = true;
        $messages['login'] = "Merci de renseigner votre login";
        $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit();
    } elseif (!filter_var($_POST['login'], FILTER_VALIDATE_LOGIN)) {
        $errors['login'] = true;
        $messages['login'] = "Merci de renseigner un login valide";
        $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit();
    }
    elseif (strlen($_POST['login']) < 4 || strlen($_POST['login']) >100) {
        $errors['login'] = true;
        $messages['login'] = "Le login ne doit pas être en dessous de 4 caractère ni au dessus de 100";
        $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit();
    } 

    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = true;
        $messages['password'] = "Merci de renseigner le mot de passe";
        $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit();
    }     
    elseif (strlen($_POST['password']) < 4 || strlen($_POST['password']) >100) {
        $errors['password'] = true;
        $messages['password'] = "Le mot de passe ne doit pas être en dessous de 4 caractère ni au dessus de 100";
        $url = 'login.php';
        header("Location: {$url}", true, 302);
        exit();
    } 
    if (!$errors) {
        dump('ok');
    }
}

// affichage du rendu d'un template
echo $twig->render('login.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'formData' => $formData,
]);


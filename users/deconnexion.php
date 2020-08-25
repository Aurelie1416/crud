<?php
require_once '../include/hello.php';
require_once '../include/connect.php';
// // on supprime le cookie
// setcookie('remember', '', ['path' => '/blog', 'expires' => 1]);
// on supprime la partie "user" de SESSION
unset($_SESSION['user']);
if(isset($_SERVER['HTTP_REFERER'])){
    header('Location: '.$_SERVER['HTTP_REFERER']);
}
else{
    header('Location: '.URL);
}
?>
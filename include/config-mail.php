<?php
// PHPMailer est orienté objet 
// on appelle ces classes avec use
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// on importe les fichiers de PHPMailer
require_once __DIR__.'/../PHPMailer/src/Exception.php';
require_once __DIR__.'/../PHPMailer/src/PHPMailer.php';
require_once __DIR__.'/../PHPMailer/src/SMTP.php';

// on instancie PHPMailer 
$sendmail = new PHPMailer();

// on configure le serveul SMTP
$sendmail->isSMTP();

// on configure l'encodage des caractères en UTF-8
$sendmail->CharSet = "UTF-8";

// on définit l'hôte du serveur
$sendmail->Host = 'localhost';

// on définit le port du serveur
$sendmail->Port = 1025;
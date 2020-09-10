<?php
//Configuration de mon serveur

//PHPMailer est orienté objet
//On appelle ses classes avec use
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


//On isntancie PHPMailer

$sendmail = new PHPMailer();

//On configure le serveur SMTP
$sendmail->isSMTP();

//On configure l'encodage des caracteres en UTF-8
$sendmail->CharSet = "UTF-8";

//On defini l'hote du serveur le nom du serveur
$sendmail->Host = 'localhost';//le famaux maillog

//On definit le port du serveur
$sendmail->Port = 1025;

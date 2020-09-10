<?php

require_once 'vendor/autoload.php'; //Cette inclusion doit etre toujours la premiere
require_once 'inc/config-mail.php';
$secret = '6LcKWsoZAAAAAB2UPQuWkbWfMcF4kRuLIpvqHk3S';

use PHPMailer\PHPMailer\Exception;

//On verifie que mon POST n'est pas vide
if (!empty($_POST)) {
    if (
        isset($_POST['nom']) && !empty($_POST['nom'])
        && isset($_POST['prenom']) && !empty($_POST['prenom'])
        && isset($_POST['email']) && !empty($_POST['email'])
        && isset($_POST['message']) && !empty($_POST['message'])
    ) {

        //On recuperer et on nettoie les données
        $nom = strip_tags($_POST['nom']);
        $prenom = strip_tags($_POST['prenom']);
        $mail = strip_tags($_POST['email']);
        $message = htmlspecialchars($_POST['message']);
        //On verifie la validité de l'email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            die('email invalide');
            header('Location: index.php');
        } else {
            $mail = $_POST['email'];
        }

        //Pour cocher le captcha cle 
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
        //On peut autoriser un nom de domaine directement dans la methode setExpectedHostname(),ici localhost
        $resp = $recaptcha->setExpectedHostname('localhost');
        //$_SERVER['REMOTE_ADDR'] me permets d'obtenir l'adresse IP de l'internaute
        //En local, puisque serveur et client sont sur le meme ordinateur, cette variable retournera 127.0.0.1
        //$_POST['g-recaptcha-response'] contient le resultat
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        if ($resp->isSuccess()) {
            // Verified!


            //On crée le mail
            try {
                //  On va definir l'expediteur du mail
                $sendmail->setFrom('admin@domaine.fr', 'Monica');

                //On defini le/les destinataires(s)
                $sendmail->addAddress($mail, $prenom, $nom);

                //On definit le sujet du mail
                $sendmail->Subject = 'Formulaire de Contact';

                //On active le HTML
                $sendmail->isHTML();

                //On ecrit le contenu du mail en HTML
                $sendmail->Body = "<h1>Message de contact</h1>
                            <p>Vous $prenom $nom etes inscrit(e) avec $mail a mon Blog amuse toi bien parce que ici on danse la Macarena ahhhh ay </p>";

                //On peut ecrire en texte brut
                $sendmail->AltBody = "Coucou $prenom.$nom";

                //On envoie le mail
                $sendmail->send();
                //echo "Nouveu Pignon ajouté";

            } catch (Exception $e) {
                //  Ici le mail n'est pas parti
                echo 'Erreur : ' . $e->errorMessage();
            }


        } else {
            $_SESSION['message'][] = "Le reCaptcha est invalide";
        }


    } else {
        $_SESSION['message'][] = "Le formulaire est incomplet";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Formulaire de Contact</title>
</head>

<body>
    <section>
        <h2>Formulaire De Contact</h2>
        <form method="POST">


        <?php
            if (isset($_SESSION['message']) && !empty($_SESSION['message'])) :
                
                unset($_SESSION['message']);
        endif;
        ?>

            <div>
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom">
                <div>
                    <div>
                        <label for="prenom">Prenom</label>
                        <input type="text" id="prenom" name="prenom">
                        <div>
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email">
                        </div>
                        <div>
                            <label for="message">Message</label>
                            <textarea name="message" id="message"></textarea>
                        </div>
                        <div>
                            <div class="g-recaptcha" data-sitekey="6LcKWsoZAAAAAKf8yVaUF1LSZ3ndkT9RkPOflWDR"></div>
                            <br />
                        </div>
                        <button>Valider</button>
        </form>
    </section>
    <section>

        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>
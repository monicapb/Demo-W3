<?php
/*Dans l'ordre
=>session_start()(si necessaire)
=>include/require
=>use
*/
//Permets de recuperer les package installer via composer
require_once 'vendor/autoload.php'; //Cette inclusion doit etre toujours la premiere
use Stichoza\GoogleTranslate\GoogleTranslate;

//On verifie que mon POST n'est pas vide
//Mon message ests rempli
if (!empty($_POST)) {
    if (isset($_POST['message']) && !empty($_POST['message'])) {

        //On recuperer et on nettoie les données
        $message = strip_tags($_POST['message']); //Retire les balises html et php

        if(isset($_POST['lang']) && !empty($_POST['lang'])){
            $lang = strip_tags($_POST['lang']);
        }
        else{
            $lang = 'en'; //Par defaut , on ferra la traduction en anglais
        }
        
        $tr = new GoogleTranslate();
        $tr->setSource(); //Langue source
        $tr->setTarget($lang); //Langue cible
        $messageTranslated = $tr->translate($message);//Permet de traduire le message
       
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire dans une autre langue</title>
</head>

<body>
    <section>
        <h2>Formulaire Bilangue</h2>
        <form method="POST">

        <select name="lang">
        <option value="en">Anglais</option>
        <option value="es">Espanõl</option>
        <option value="ru">Russian</option>
        <option value="it">Italian</option>
        </select>


            <div>
                <label for="message">Message : </label>
                <textarea name="message" id="message"><?=(isset($message))? $message : '';?></textarea>
            </div>

            <button>Valider</button>
        </form>

        <?php if(isset($messageTranslated) && !empty($messageTranslated)):?>
        <hr>
        <p><?=$messageTranslated?></p>
        <?php endif ?>
    </section>
    
</body>

</html>
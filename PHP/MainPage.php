<?php

    session_start();
?>

<?php
if (!empty($_POST)) {
    $nom = ($_POST['nom']);
    $email = ($_POST['email']);
    $message = ($_POST['message']);

    // vérification du formulaire 
    if(!strpos($_POST['email'],'@')){//sans @, ce n'est pas un email
        echo "email incorrect";
    
    } elseif(empty($_POST['nom'])){ //nom vide => on arrête l'exécution du script
    echo "Le champ nom est vide.";
    } elseif(!preg_match("#^[A-Za-z0-9]+$#",$_POST['nom'])){//nom contenant des caractères interdits
    echo "Le nom doit être renseigné en lettres sans accents, sans caractères spéciaux.";
    } elseif(strlen($_POST['pseudo'])>32){//nom trop long
    echo "Le nom est trop long, il dépasse 32 caractères.";

    } elseif(empty($_POST['message'])){//mot de passe vide
    echo "Le champ message est vide.";
    } elseif(strlen($_POST['mdp'])>1000){//mdp trop long
    echo "Le message est trop long, il dépasse 1000 caractères.";
    } else {
        // entête email
        $headers  = 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=ISO-8859-1'."\n";
        $headers .= 'Reply-To: ' . $_POST['email'] . "\n";
        $headers .= 'From: "' . ucfirst(substr($_POST['email'], 0, strpos($_POST['email'], '@'))) . '"<'.$_POST['email'].'>' . "\n";
        
        mail("greenroad.caaapa@gmail.com", $_POST['nom'], $_POST['message'], $headers); 
        print "Email envoyé !";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> GreenRoad</title>
        <link rel="stylesheet" href="../CSS/MainPage.css?v=<?php echo time(); ?>"> 
    </head>


    <body>
            
        <section class="login">
            <?php
            
                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                    echo "<a class=\"btn1\" href=\"../PHP/register.php\">Inscription</a>";
                    echo "<a class=\"btn2\" href=\"../PHP/login.php\">Connexion</a>";
                }else{
                    echo "<a href=\"../PHP/userpage.php\"><img src=\"../images/profillogo.png\"></a>";
                    echo "<a class=\"btn3\" href=\"../PHP/logout.php\" >Déconnexion</a>";
                }
            ?>
        </section>

        
        <nav>
            <a href="../php/MainPage.php">
                <img src="../IMAGES/GreenRoad.gif">
            </a>
            <h1>GreenRoad</h1>
            <div class="onglets">
                <ul>
                    </li><u><a href="../php/MainPage.php">Accueil</a></u></li>
                    </li><a href="../php/caaapa.php">L'équipe</a></li>
                    </li><a href="../php/contact.php">Contact</a></li>
                    </li><a href="../php/faq.php">FAQ</a></li>
                    </li><a href="../php/cartographie.php">Cartographie</a></li>
                    </li><a href="../php/statsetdonnees.php">Statistiques</a></li>
                    </li><a href="../php/pierrito.php" class="pierrito">Pierrito Game<span><img src="../IMAGES/pierrito.png"/></span></a></li>
                </ul>
            </div>

        </nav>

        <header>
            <h2>BIENVENUE CHEZ GREENROAD</h2>
            <h3>L'innovation, notre seconde nature</h3>
        </header>
        <section class="àpropos">
            <div class="content">

                <div class="imagegauche">
                    <img src="../IMAGES/imagetoits.webp">
                </div>

                <div class="textedroite">
                    <h4>À PROPOS DE NOUS</h4>
                    <h5>
                        <p>Nos objectifs</p>
                        </p>Dans un monde de plus en plus urbanisé, garantir la place de l’humain c’est garantir la qualité de son environnement. En raison de l’évolution de notre société, il est de plus en plus difficile d'avoir un environnement sain, que ce soit à cause d’une mauvaise qualité de l’air, de la pollution sonore ou encore de la température autour de l’usager.</p>
                        </p>Afin de garantir une certaine place à l’humain dans un environnement confortable et sain, certaines entreprises font de l’écologie le cœur de leur mission, à l’instar de CAAAPA. Nous travaillons sur des projets censés rendre notre vie meilleure et respecter les enjeux écologiques de notre temps. C’est pour cette raison que notre start-up s’intéresse particulièrement aux appels d'offres de clients qui mettent en avant le désir d’améliorer notre environnement ou juste de sensibiliser.</p>
                    </h5>
                </div>

            </div>

        </section>


        <section class=contactform>
            <div class="fcf-body">

                <div id="fcf-form">
                    <h3 class="fcf-h3">Nous contacter</h3>
                
                    <form id="fcf-form-id" class="fcf-form-class" method="post" action="contact-form-process.php">
                        
                        <div class="fcf-form-group">
                            <label for="nom" class="fcf-label">Nom</label>
                            <div class="fcf-input-group">
                                <input type="text" id="nom" name="nom" class="fcf-form-control" required>
                            </div>
                        </div>
                
                        <div class="fcf-form-group">
                            <label for="email" class="fcf-label">Email</label>
                            <div class="fcf-input-group">
                                <input type="email" id="email" name="email" class="fcf-form-control" required>
                            </div>
                        </div>
                
                        <div class="fcf-form-group">
                            <label for="message" class="fcf-label">Message</label>
                            <div class="fcf-input-group">
                                <textarea id="message" name="message" class="fcf-form-control" rows="6" maxlength="3000" required></textarea>
                            </div>
                        </div>
                
                        <div class="fcf-form-group">
                            <button type="submit" id="fcf-button" class="fcf-btn fcf-btn-primary fcf-btn-lg fcf-btn-block">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <section class=map>
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d10502.703125190203!2d2.3280245!3d48.8453227!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9bd1ac22c478d56e!2sISEP!5e0!3m2!1sfr!2sfr!4v1636974983219!5m2!1sfr!2sfr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" ></iframe>
        </section>
    </body>

    <!--Footer-->
<?php include_once('footer.php'); ?>

</html>
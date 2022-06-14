<?php
// Initialiser la session
session_start();

// Connection à la BDD
include_once('config.php');
 
// Vérifiez si l'utilisateur est connecté, sinon le redirige à la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php

//Affichage de la liste déroulante des villes










//Traitement du formulaire
//Si le champ date est vide, on prend la date actuelle
//Si le champ ville est vide, on prend la dernière ville cherchée ==> utiliser les fonctions de session








?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GreenRoad</title>
    <link rel="stylesheet" href="../CSS/cartographie.css?v=<?php echo time(); ?>"> 

    <!--Affichage de la map-->
    <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
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
                </li><a href="../php/MainPage.php">Accueil</a></li>
                </li><a href="../php/caaapa.php">L'équipe</a></li>
                </li><a href="../php/contact.php">Contact</a></li>
                </li><a href="../php/faq.php">FAQ</a></li>
                </li><u><a href="../php/cartographie.php">Cartographie</a></u></li>
                </li><a href="../php/statsetdonnees.php">Statistiques</a></li>
                </li><a href="../php/pierrito.php" class="pierrito">Pierrito Game<span><img src="../IMAGES/pierrito.png"/></span></a></li>
            </ul>
        </div>
    </nav>

    

    <section class="maps">
        <form class="time" id="formulaire">
            <!--Choix de la date et de l'heure-->
            <input type="datetime-local" id="start" min="2021-12-21T00:00">

            <!--Choix du lieu-->
            <select id="listeVille">
                <option value="0">Veuillez choisir une ville</option>
                <option value="1">Issy-les-Moulineaux</option>
                <option value="2">Lille</option>
                <option value="3">Lyon</option>  
                <option value="4">Paris</option>
            </select>

            <!--Validation des champs-->
            <input type='button' value='Valider' id="reset"/>

        </form>

        <div class="graphiques">
            <section id="ongletsDonnees">
              <ul id="ongletsUl">
                <li><a id="a1" href="#" class="active">Pollution sonore</a></li>
                <li><a id="a2" href="#">Pollution CO2</a></li>
              </ul>
              <div id="ongletsContent"></div>
            </section>

            <div class="graphiques">
                <script type="text/javascript" src="../JAVASCRIPT/cartographieOnglets.js"></script>
            </div>
        </div>

    </section>
   

</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>
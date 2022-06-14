<?php
// Initialiser la session
session_start();
 
// Vérifiez si l'utilisateur est connecté, sinon le redirige à la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"]==1){
    echo "<h1> Vous n'êtes pas autorisé à accéder à cette page ! </h1> ";
    echo "Veuillez retourner sur la <a href=\"../php/MainPage.php\">page d'accueil</a>. ";
    exit;
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
            <a class="btn3" href="../PHP/logout.php">Déconnexion</a>
        </section>

        
        <nav>
            <a href="../php/MainPage.php">
                <img src="../IMAGES/GreenRoad.gif">
            </a>
            <h1>GreenRoad - Espace Administrateur</h1>
            <div class="onglets">
                <ul>
                    </li><a href="../php/accueiladmin.php">Accueil</a></li>
                    </li><a href="../php/adminusers.php">Gestion utilisateurs</a></li>
                    </li><u><a href="../php/admincapteurs.php">Gestion capteurs</a></u></li>
                    </li><a href="../php/adminfaq.php">Gestion FAQ</a></li>
                </ul>
            </div>
        </nav>

        <h1> En cours de développement... </h1>



        
    </body>
</html>
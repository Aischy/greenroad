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

<?php
//---------------connexion à la BDD---------------------------------
include_once('config.php');

//-----------Dernières connexions administrateur------------------
$stmt = $db->query("SELECT dateCo, adresseIP, idUser FROM connexionsadmin ORDER BY dateCo DESC LIMIT 0,999");
$statement= $db->query("SELECT idUser, pseudo FROM users ");
$conns = $stmt->fetch_all(MYSQLI_ASSOC);
$users = $statement->fetch_all(MYSQLI_ASSOC);





?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> GreenRoad</title>
        <link rel="stylesheet" href="../CSS/accueiladmin.css?v=<?php echo time(); ?>"> 
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
                    </li><u><a href="../php/accueiladmin.php">Accueil</a></u></li>
                    </li><a href="../php/adminusers.php">Gestion utilisateurs</a></li>
                    </li><a href="../php/admincapteurs.php">Gestion capteurs</a></li>
                    </li><a href="../php/adminfaq.php">Gestion FAQ</a></li>
                </ul>
            </div>
        </nav>

        <h3 class="titreadmin">Dernières connexions administrateur</h3>
        <div class="adminbox">
            <p>
                <?php 
                foreach($conns as $conn){
                    foreach($users as $user){
                        if($conn['idUser']==$user['idUser']){
                            echo $conn['dateCo']." : ".$user['pseudo']." (".$conn['adresseIP'].") </br>";
                        }
                    } 
                }
                ?>
            </p>
        </div>
<!--
        <h3 class="titreadmin">Dernières actions</h3>
        <div class="adminbox">
            <p>
                <?php //PHP pas encore mis en place, juste pour faire joli dans le cadre

                foreach($conns as $conn){
                    echo $conn['dateCo']." : user xx (".$conn['adresseIP'].") a fait ceci cela </br>"; 
                }?>
            </p>
        </div>

-->

        
    </body>
</html>
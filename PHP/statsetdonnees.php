<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenRoad</title>
    <link rel="stylesheet" href="../CSS/statsetdonnees.css?v=<?php echo time(); ?>"> 
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
                </li><a href="../php/cartographie.php">Cartographie</a></li>
                </li><u><a href="../php/statsetdonnees.php">Statistiques</a></u></li>
                </li><a href="../php/pierrito.php" class="pierrito">Pierrito Game<span><img src="../IMAGES/pierrito.png"/></span></a></li>
            </ul>
        </div>

    </nav>

    <section class="stats">
        <div class="time">
            <input type="date" id="start" min="2021-12" value="2021-12">
            <select>
            <option>Paris</option>
            <option value="1">Issy-les-Moulineaux</option>
            <option value="2">Lyon</option>
            <option value="3">Angers</option>
            <option value="4">Lille</option>
            </select>
        </div>



        <div class="graphiques">
          <section id="ongletsDonnees">
            <ul id="ongletsUl">
              <li><a id="a1" href="#" class="active">Pollution sonore</a></li>
              <li><a id="a2" href="#">Pollution CO2</a></li>
            </ul>
            <div id="ongletsContent"></div>
          </section>

          <div class="graphiques">
            <script type="text/javascript" src="../JAVASCRIPT/graphiqueOnglets.js"></script>
          </div>
        </div>

    </section>

</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html> 
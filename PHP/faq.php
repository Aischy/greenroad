<?php

    session_start();

//------CONNEXION FAQ---------------

include_once('config.php');

//------RECUPERATION DES QUESTIONS/REPONSES-----------
$stmt = $db->query("SELECT question, reponse FROM questions");
$faq = $stmt->fetch_all(MYSQLI_ASSOC);










?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GreenRoad</title>
    <link rel="stylesheet" href="../CSS/faq.css?v=<?php echo time(); ?>"> 
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
                </li><u><a href="../php/faq.php">FAQ</a></u></li>
                </li><a href="../php/cartographie.php">Cartographie</a></li>
                </li><a href="../php/statsetdonnees.php">Statistiques</a></li>
                </li><a href="../php/pierrito.php" class="pierrito">Pierrito Game<span><img src="../IMAGES/pierrito.png"/></span></a></li>
            </ul>
        </div>

    </nav>

    <section class="faq">
        <h1> Foire aux questions </h1>
        <?php

        foreach ($faq as $sentence){
            echo '
                <button class="collapsible">'.$sentence['question'].'</button>
                <div class="contentcollaps">
                  <p>'.$sentence['reponse'].'</p>
                </div>
                <br/><br/>
            ';
        }

        ?>
        <script>
            var coll = document.getElementsByClassName("collapsible");
            var i;

            for (i = 0; i < coll.length; i++) {
              coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var contentcollaps = this.nextElementSibling;
                if (contentcollaps.style.maxHeight){
                  contentcollaps.style.maxHeight = null;
                } else {
                  contentcollaps.style.maxHeight = contentcollaps.scrollHeight + "px";
                } 
              });
            }
        </script>
    </section>


</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>
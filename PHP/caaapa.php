<?php

  session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> GreenRoad</title>
    <link rel="stylesheet" href="../CSS/caaapa.css?v=<?php echo time(); ?>"> 
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
            <img src="../IMAGES/GreenRoad.png">
        </a>
        <h1>GreenRoad</h1>
        <div class="onglets">
            <ul>
                </li><a href="../php/MainPage.php">Accueil</a></li>
                </li><u><a href="../php/caaapa.php">L'équipe</a></u></li>
                </li><a href="../php/contact.php">Contact</a></li>
                </li><a href="../php/faq.php">FAQ</a></li>
                </li><a href="../php/cartographie.php">Cartographie</a></li>
                </li><a href="../php/statsetdonnees.php">Statistiques</a></li>
                </li><a href="../php/pierrito.php" class="pierrito">Pierrito Game<span><img src="../IMAGES/pierrito.png"/></span></a></li>
            </ul>
        </div>

    </nav>
    <div class="caaapa">

      <div class="imagegauche">
        <img src="../IMAGES/caaapa.gif">
      </div>

      <div class="textedroite">
          <h4>À PROPOS DE NOUS</h4>
          <h5>
              <p>Qui sommes-nous ?</p>
              <p>CAAAPA est une start-up formée par 6 étudiants issus de l’ISEP, école d’ingénieurs du numérique à Paris. Nous nous sommes rencontrés en première année du cycle ingénieur, lors de notre apprentissage par projets et, à l’issue de ce travail d’équipe fructifiant, nous avons décidé de monter notre start-up par le biais de l’incubateur mis à notre disposition à l’ISEP.</p>
              <p>Grâce à notre formation, nous sommes sensibilisés aux contraintes environnementales et à l’ingénierie verte, et avons décidé de mettre l'écologie au cœur de notre mission. C’est la raison pour laquelle nos objectifs peuvent se résumer en une seule phrase : proposer des solutions technologiques vertes de qualité et dans les règles de l’art.</p>
          </h5>
      </div>

    </div>

    <article class="flow">
        <div class="team">
          <ul style="list-style: none;" class="auto-grid" role="list">
            <li>
              <a href="https://www.linkedin.com/in/ayah-al-mutwaly/" target="_blank" class="profile">
                <h2 class="profile__name">Ayah AL MUTWALY</h2>
                <p></p>
                <img src="../images/ayah.jpg" />
              </a>
            </li>
            <li>
              <a href="https://www.linkedin.com/in/achraf-jalal/" target="_blank" class="profile">
                <h2 class="profile__name">Achraf JALAL</h2>
                <p></p>
                <img src="../images/achraf.jpg" />
              </a>
            </li>
            <li>
              <a href="https://www.linkedin.com/in/capucine-barr%C3%A9/" target="_blank" class="profile">
                <h2 class="profile__name">Capucine BARRÉ</h2>
                <p></p>
                <img src="../images/capucine.png" />
              </a>
            </li>
            <li>
              <a href="" target="_blank" class="profile">
                <h2 class="profile__name">Pierre ANFOSSI</h2>
                <p></p>
                <img src="https://source.unsplash.com/55JRsxcAiWE/800x800" />
              </a>
            </li>
            <li>
              <a href="" target="_blank" class="profile">
                <h2 class="profile__name">Arthur TOTAL</h2>
                <p></p>
                <img src="../images/arthur.jpg" />
              </a>
            </li>
            <li>
              <a href="https://www.linkedin.com/in/adil-lazar/" target="_blank" class="profile">
                <h2 class="profile__name">Adil LAZAR</h2>
                <p></p>
                <img src="../images/adil.jfif" />
              </a>
            </li>
          </ul>
        </div>
    </article>

</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>
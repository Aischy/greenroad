<?php
// Initialiser la session
session_start();
 
// Vérifiez si l'utilisateur est connecté, sinon le redirige à la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<?php
//---------------connexion à la BDD---------------------------------
include_once('config.php');

//---------stats du joueur------------------------
$id=$_SESSION["id"];
$jeu = $db->prepare("SELECT recordJeu, dateRecordJeu, lastScoreJeu, dateLastScore FROM users WHERE idUser='$id' ");
$jeu->execute();
$jeu->bind_result($recordJeu,$dateRecord,$lastScore,$dateLast);
while ($jeu->fetch()){      
}

//-----------classement des joueurs------------------
$stmt = $db->query("SELECT pseudo, recordJeu FROM users ORDER BY recordJeu DESC LIMIT 0,6");
$players = $stmt->fetch_all(MYSQLI_ASSOC);

?>











<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> GreenRoad</title>
    <link rel="stylesheet" href="../CSS/pierrito.css" /> 
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
                </li><a href="../php/caaapa.php">L'équipe</a></li>
                </li><a href="../php/contact.php">Contact</a></li>
                </li><a href="../php/faq.php">FAQ</a></li>
                </li><a href="../php/cartographie.php">Cartographie</a></li>
                </li><a href="../php/statsetdonnees.php">Statistiques</a></li>
                </li><u><a href="../php/pierrito.php">Pierrito Game</a></u></li>
            </ul>
        </div>
    </nav>

    <iframe src="pierrito1.php" title="Pierrito Game" width="1200" height="600" align="center"></iframe>

    <br/><br/>

    <div class="donnees" id="regles">
        <button class="collapsible">Règles du jeu</button>
        <div class="contentcollaps">
            <p><br/>
            Dans Crossy Road vous incarnez un petit animal ou personnage et devez avancez le plus loin possible pas à pas, sans vous faire percuter par une voiture, un camion, ou sans tomber dans un piège. En plus de ça, l'écran avance de plus en plus vite vous incitant donc à prendre vos décisions rapidement.</br></br>

            Il arrive même parfois que des TGV passent à très grande vitesse sur votre route, à vous de les anticiper à l'aide des feus rouge. Vous devrez aussi parfois traverser des cours d'eaux en sautant sur des bouts de bois et attention de ne pas tomber dans l'eau... De nombreux pièges sont mis en place dans le jeu, à vous de les éviter et d'aller le plus loin possible.</br></br>

            Crossy Road dispose aussi d'un système de pièces d'or à ramasser (un peu à la mario kart). Plus vous ramasserez des pièces et plus vous aurez droit à des cadeaux en fin de partie.</br></br>

            Crossy Road vous propose dans sa version navigateur (PC et Mac) de jouer avec le Poulet Dodu, le Robot Rouillé, le Chat Heureux, le Canard Boiteux, l'Escargot Véloce et d'autres personnages surprise. Ces personnages sont à débloquer au fil de votre avancée dans le jeu.</br></br>

            Crossy Road est installable gratuitement sur iOs et Android, et 12 personnages supplémentaires ainsi qu'un nouveau monde océanique vous y sont offerts. Le jeu est parfait pour jouer lorsqu'on a un peu de temps libre, ou que l'on s'ennuis.</br></br>
            </p>
        </div>
    </div>

    <br/><br/>


    <div class="donnees" id="stats">
        <button class="collapsible">Statistiques personnelles</button>
        <div class="contentcollaps">
            <p> <br/>
                Votre dernier score : <b><?php echo $lastScore." points"; ?></b> réalisé le : <?php echo $dateLast; ?> </br></br>
                Votre record actuel: <b><?php echo $recordJeu." points"; ?></b> réalisé le : <?php echo $dateRecord; ?>  </br></br>
            </p>
        </div>
    </div>

    <br/><br/>

    <div class="donnees" id="classement">
        <button class="collapsible">Classement des meilleurs joueurs</button>
        <div class="contentcollaps">
            <p><br/>
                <div class="classement">
                    <fieldset id="classement_joueurs">
                        <div class="podium" style="display:block;">
                            <div class="ligne ligne1">
                                <div class="joueur r">1. <?php echo $players[0]['pseudo']; ?> 
                                <div class="score">Score : <?php echo $players[0]['recordJeu']; ?> </div>
                                </div>
                            </div>
                            <div class="ligne ligne2">

                                <div class="joueur">2. <?php echo $players[1]['pseudo']; ?> 
                                    <div class="score">Score : <?php echo $players[1]['recordJeu']; ?> </div>
                                </div>

                                <div class="joueur r">3. <?php echo $players[2]['pseudo']; ?> 
                                    <div class="score">Score : <?php echo $players[2]['recordJeu']; ?> </div>
                                </div>
                            </div>
                            <div class="ligne ligne3">

                                <div class="joueur b">4. <?php echo $players[3]['pseudo']; ?> 
                                    <div class="score">Score : <?php echo $players[3]['recordJeu']; ?> </div>
                                </div>

                                <div class="joueur b">5. <?php echo $players[4]['pseudo']; ?> 
                                    <div class="score">Score : <?php echo $players[4]['recordJeu']; ?> </div>
                                </div>

                                <div class="joueur b r">6. <?php echo $players[5]['pseudo']; ?> 
                                    <div class="score">Score : <?php echo $players[5]['recordJeu']; ?> </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
        </div>
    </p>
        
    </div>
    <br/><br/>

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




</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>
<?php
// Initialiser la session
session_start();
 
// Vérifiez si l'utilisateur est connecté, sinon le redirige à la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

//---------------connexion à la BDD---------------------------------
include_once('config.php');

//----------Récupération des données du joueur-----------------
$id=$_SESSION["id"];
$user = $db->prepare("SELECT pseudo, mail, password, dateInscription, recordJeu, dateRecordJeu, lastScoreJeu, dateLastScore FROM users WHERE idUser='$id' ");
$user->execute();
$user->bind_result($pseudo, $mail, $password, $dateInscription, $recordJeu,$dateRecord,$lastScore,$dateLast);
while ($user->fetch()){}      

//-----------------FORMULAIRES-------------------------

//FORMULAIRE 1 : CHANGEMENT D'ADRESSE MAIL
if(!empty($_POST['email']) And !empty($_POST['mdp'])) {
    //VERIFICATION DU FORMULAIRE 1
    if(!strpos($_POST['email'],'@')) {//sans @, ce n'est pas un email
        echo "email incorrect";
    }elseif($_POST['email']==$mail) {
        echo "Il s'agit de la même adresse mail";
    }elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE mail = '".$_POST['email']."'"))) {
        echo "Ce mail est déjà utilisé.";
    }elseif(md5($_POST['mdp'])!=$password){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 1
        $newemail=$_POST['email'];
        $stmt = $db->prepare("UPDATE users SET mail='$newemail' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification de l'adresse mail effectuée. ";
        }else{
          print $db->error;
        }
    }

//FORMULAIRE 2 : CHANGEMENT DU MOT DE PASSE
}elseif(!empty($_POST['mdp1']) And !empty($_POST['mdp2']) And !empty($_POST['mdp3'])) {
    //VERIFICATION DU FORMULAIRE 2
    if(md5($_POST['mdp1'])!=$password){
        echo "Mot de passe actuel incorrect";
    }elseif($_POST['mdp2']!=$_POST['mdp3']){
        echo "Confirmation du mot de passe incorrecte";
    }elseif($_POST['mdp1']==$_POST['mdp2']){
        echo "Il s'agit du même mot de passe";
    }elseif(strlen($_POST['mdp2'])>128){//mdp trop long
      echo "Le pseudo est trop long, il dépasse 128 caractères.";
    }elseif(strlen($_POST['mdp2'])<8){//mdp trop court
      echo "Le pseudo est trop court, il doit faire au moins 8 caractères.";
    }else{
        //EXECUTION DU FORMULAIRE 2
        $newmdp=md5($_POST['mdp2']);
        $stmt = $db->prepare("UPDATE users SET password='$newmdp' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification du mot de passe effectuée. ";
        }else{
          print $db->error;
        }
    }


//FORMULAIRE 3 : SUPPRESSION DEFINITIVE DU COMPTE (selon la RGPD)
}elseif(!empty($_POST['delete']) And !empty($_POST['mdp4'])) {
    //VERIFICATION DU FORMULAIRE 3
    if(md5($_POST['mdp4'])!=$password){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 3
        $deleteConn = $db->prepare("DELETE FROM connexions WHERE idUser='$id'");
        $deleteConn -> execute();

        $deleteConnAdmin = $db->prepare("DELETE FROM connexionsadmin WHERE idUser='$id'");
        $deleteConnAdmin -> execute();

        $deleteCompte = $db->prepare("DELETE FROM users WHERE idUser='$id'");
        if($deleteCompte -> execute()){
          echo "Compte supprimé. ";
        }else{
          print $db->error;
        }
        session_destroy();

    }
}







?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Userpage</title>
    <link rel="stylesheet" href="../CSS/userpage.css?v=<?php echo time(); ?>">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
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
                    </li><a href="../php/statsetdonnees.php">Statistiques</a></li>
                    </li><a href="../php/pierrito.php" class="pierrito">Pierrito Game<span><img src="../IMAGES/pierrito.png"/></span></a></li>
                </ul>
            </div>

        </nav>

        <div class="userpage">
            <img src="../IMAGES/profillogo1.png"/>
            <h1 class="my-5"><b><?php echo $_SESSION["username"]; ?></b></h1>
            <div id="informations">
                <table>
                    <tr>
                        <td class="info">
                            <table>
                                <h3><b><u>Informations personnelles</u></b></h3></br>
                                <p> Pseudo :  <?php echo $pseudo; ?></br>
                                    E-mail : <?php echo $mail; ?> <a href="#id01"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a></br>
                                    Date d'inscription : <?php echo $dateInscription; ?> </br>
                                    <a href="#id02"><button>Modifier le mot de passe</button></a>
                                    <a href="#id03"><button>Supprimer le compte</button></a>
                                </p>
                            </table>
                        </td>
                        <td class="espace"></td>
                        <td class="info">
                            <table>
                                <h3><b><u>Pierrito</u></b></h3></br>
                                <p>Record : <?php echo $recordJeu." points "; ?> </br>
                                    <blockquote>Le : <?php echo $dateRecord; ?> </blockquote></br>
                                Dernier score : <?php echo $lastScore." points "; ?> </br>
                                    <blockquote>Le : <?php echo $dateLast; ?> </blockquote>
                                </p>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>





            <!--Les différents formulaires de modification-->

            <!--Formulaire 1 : modification de l'adresse mail-->
            <div id="id01" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier l'adresse e-mail</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="email">Entrez votre nouvelle adresse e-mail</label><br/>
                            <input name="email" type="text" id="email"/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp" type="password" id="mdp"/><br/>
                        </div>  
                        <div class="form-group">
                            <input type="submit" class="confirmation1" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>  
                    </form>
                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 

            <!--Formulaire 2 : modification du mot de passe-->
            <div id="id02" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier le mot de passe</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="mdp1">Entrez votre mot de passe actuel</label><br/>
                            <input name="mdp1" type="password" id="mdp1"/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp2">Entrez votre nouveau mot de passe</label><br/>
                            <input name="mdp2" type="password" id="mdp2"/><br/>
                        </div>  
                        <div class="form-group">
                            <label for="mdp3">Confirmez votre nouveau mot de passe</label><br/>
                            <input name="mdp3" type="password" id="mdp3"/><br/>
                        </div>  
                        <div class="form-group">
                            <input type="submit" class="confirmation2" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>  
                    </form>
                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 

            <!--Formulaire 3 : suppression de compte-->
            <div id="id03" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Supprimer le compte</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="delete">Cochez pour <b>supprimer définitivement</b>votre compte</label>
                            <input name="delete" type="checkbox" id="delete">
                        </div>
                        <div class="form-group">
                            <label for="mdp4">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp4" type="password" id="mdp4"/><br/>
                        </div>  
                        <div class="form-group">
                            <input type="submit" class="confirmation3" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>
                    </form>
                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 











        </div></div>
    </div>












</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>
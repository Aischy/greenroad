<?php $search = 0; ?>

<?php
//----------Initialiser la session----------
session_start();
 
//Vérifiez si l'utilisateur est connecté, sinon le redirige à la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"]==1){
    echo "<h1> Vous n'êtes pas autorisé à accéder à cette page ! </h1> ";
    echo "Veuillez retourner sur la <a href=\"../php/MainPage.php\">page d'accueil</a>. ";
    exit;
}

?>

<?php 

//----------Connexion BDD----------------------------------------
include_once('config.php');

//----------Récupération du mot de passe (crypté) de l'admin/gestionnaire----------
$idAdmin=$_SESSION["id"];
$user = $db->prepare("SELECT password FROM users WHERE idUser='$idAdmin' ");
$user->execute();
$user->bind_result($passwordAdmin);
while ($user->fetch()){} 


//----------Récupération de la recherche--------------------
if (empty($_POST['recherche'])){
    $search = 0;
    $recherche = "XXXXXXXX";
}

 $recherche = isset($_POST['recherche']) ? $_POST['recherche'] : '';

 // la requete mysql
 $query = $db->query("SELECT idUser, pseudo, mail, dateInscription, recordJeu, dateRecordJeu, lastScoreJeu, dateLastScore, Role, etat, users.idRole FROM users INNER JOIN etats ON users.idEtat = etats.idEtat INNER JOIN roles ON users.idRole = roles.idRole WHERE pseudo LIKE '%$recherche%' OR idUser LIKE '%$recherche%' LIMIT 1");

 // affichage du résultat
 while( $r = mysqli_fetch_array($query)){
    $idUser = $r['idUser'];
    $pseudo = $r['pseudo'];
    $mail = $r['mail'];
    $dateInscription = $r['dateInscription'];
    $recordJeu = $r['recordJeu'];
    $dateRecord = $r['dateRecordJeu'];
    $lastScore = $r['lastScoreJeu'];
    $dateLast = $r['dateLastScore'];
    $role = $r['Role'];
    $etat = $r['etat'];
    $idRoleUser = $r['idRole'];
    $search = 1;
 }

//récupération de l'historique des connexions du joueur
 $stmt = $db->query("SELECT dateCo, adresseIP FROM connexions INNER JOIN users ON connexions.idUser = users.idUser WHERE pseudo LIKE '%$recherche%' ORDER BY dateCo DESC LIMIT 0,999");
 $conns = $stmt->fetch_all(MYSQLI_ASSOC);


//--------------------FORMULAIRES-----------------------------------

//FORMULAIRE 1 : CHANGEMENT DE PSEUDO
if(!empty($_POST['pseudo']) And !empty($_POST['mdp0'])) {
    //VERIFICATION DU FORMULAIRE 1
    if(empty($_POST['pseudo'])){ //pseudo vide => on arrête l'exécution du script
      echo "Le champ Pseudo est vide.";
    } elseif(!preg_match("#^[A-Za-z0-9]+$#",$_POST['pseudo'])){//pseudo contenant des caractères interdits
      echo "Le Pseudo doit être renseigné en lettres sans accents, sans caractères spéciaux.";
    } elseif(strlen($_POST['pseudo'])>32){//pseudo trop long
      echo "Le pseudo est trop long, il dépasse 32 caractères.";
    } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE pseudo = '".$_POST['pseudo']."'"))) {
    echo "Ce pseudo est déjà utilisé.";
    }elseif(md5($_POST['mdp0'])!=$passwordAdmin){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 1
        $id = $_POST['id'];
        $newpseudo=$_POST['pseudo'];
        $stmt = $db->prepare("UPDATE users SET pseudo='$newpseudo' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification du pseudo effectuée. ";
        }else{
          print $db->error;
        }
    }

//FORMULAIRE 2 : CHANGEMENT D'ADRESSE MAIL
}else if(!empty($_POST['email']) And !empty($_POST['mdp'])) {
    //VERIFICATION DU FORMULAIRE 2
    if(!strpos($_POST['email'],'@')) {//sans @, ce n'est pas un email
        echo "email incorrect";
    }elseif($_POST['email']==$mail) {
        echo "Il s'agit de la même adresse mail";
    }elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE mail = '".$_POST['email']."'"))) {
        echo "Ce mail est déjà utilisé.";
    }elseif(md5($_POST['mdp'])!=$passwordAdmin){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 2
        $id = $_POST['id'];
        $newemail=$_POST['email'];
        $stmt = $db->prepare("UPDATE users SET mail='$newemail' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification de l'adresse mail effectuée. ";
        }else{
          print $db->error;
        }
    }

//FORMULAIRE 3 : CHANGEMENT DU MOT DE PASSE
}elseif(!empty($_POST['mdp1']) And !empty($_POST['mdp2']) And !empty($_POST['mdp3'])) {
    //VERIFICATION DU FORMULAIRE 3
    if(md5($_POST['mdp1'])!=$passwordAdmin){
        echo "Mot de passe actuel incorrect";
    }elseif($_POST['mdp2']!=$_POST['mdp3']){
        echo "Confirmation du mot de passe incorrecte";
    }elseif(strlen($_POST['mdp2'])>128){//mdp trop long
      echo "Le pseudo est trop long, il dépasse 128 caractères.";
    }elseif(strlen($_POST['mdp2'])<8){//mdp trop court
      echo "Le pseudo est trop court, il doit faire au moins 8 caractères.";
    }else{
        //EXECUTION DU FORMULAIRE 3
        $id = $_POST['id'];
        $newmdp=md5($_POST['mdp2']);
        $stmt = $db->prepare("UPDATE users SET password='$newmdp' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification du mot de passe effectuée. ";
        }else{
          print $db->error;
        }
    }


//FORMULAIRE 4 : SUPPRESSION DEFINITIVE DU COMPTE (selon la RGPD)
}elseif(!empty($_POST['delete']) And !empty($_POST['mdp4'])) {
    //VERIFICATION DU FORMULAIRE 4
    if(md5($_POST['mdp4'])!=$passwordAdmin){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 4
        $id = $_POST['id'];

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

    }

//FORMULAIRE 5 : MODIFICATION DU RECORD AU JEU PIERRITO GAME
}else if(!empty($_POST['record']) And !empty($_POST['mdp5'])) {
    //VERIFICATION DU FORMULAIRE 5
    if(!is_numeric($_POST['record'])) { //Vérifie si le champ est un nombre
        echo "Le record doit être un nombre entier";
    }else if(!is_int(intval($_POST['record']))) { //Vérifie si le champ est un entier
        echo "Le record doit être un nombre entier";
    }elseif(md5($_POST['mdp5'])!=$passwordAdmin){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 5
        $id = $_POST['id'];
        $newrecord=$_POST['record'];
        $stmt = $db->prepare("UPDATE users SET recordJeu='$newrecord' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification du record effectuée. ";
        }else{
          print $db->error;
        }
    }

//FORMULAIRE 6 : MODIFICATION DU DERNIER SCORE AU JEU PIERRITO GAME
}else if(!empty($_POST['last']) And !empty($_POST['mdp6'])) {
    //VERIFICATION DU FORMULAIRE 6
    if(!is_numeric($_POST['last'])) { //Vérifie si le champ est un nombre
        echo "Le record doit être un nombre";
    }else if(!is_int(intval($_POST['last']))) { //Vérifie si le champ est un entier
        echo "Le record doit être un nombre entier";
    }elseif(md5($_POST['mdp6'])!=$passwordAdmin){
        echo "Mot de passe incorrect";
    }else{
        //EXECUTION DU FORMULAIRE 6
        $id = $_POST['id'];
        $newlastscore=$_POST['last'];
        $stmt = $db->prepare("UPDATE users SET lastScoreJeu='$newlastscore' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification du score effectuée. ";
        }else{
          print $db->error;
        }
    }

//FORMULAIRE 7 : MODIFICATION DE L'ETAT
}else if(!empty($_POST['etat']) And !empty($_POST['mdp7'])) {
    //VERIFICATION DU FORMULAIRE 7
    if(md5($_POST['mdp7'])!=$passwordAdmin) { //Vérifier le mot de passe
        echo "Mot de passe incorrect";

    }else{
        //EXECUTION DU FORMULAIRE 7
        $id = $_POST['id'];
        $newetat=$_POST['etat'];
        $stmt = $db->prepare("UPDATE users SET idEtat='$newetat' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification de l'état effectuée. ";
        }else{
          print $db->error;
        }
    }

//FORMULAIRE 8 : MODIFICATION DE LE ROLE
}else if(!empty($_POST['role']) And !empty($_POST['mdp8'])) {
    //VERIFICATION DU FORMULAIRE 8
    if(md5($_POST['mdp8'])!=$passwordAdmin) { //Vérifier le mot de passe
        echo "Mot de passe incorrect";

    }else{
        //EXECUTION DU FORMULAIRE 8
        $id = $_POST['id'];
        $newrole=$_POST['role'];
        $stmt = $db->prepare("UPDATE users SET idRole='$newrole' WHERE idUser='$id'");
        if($stmt -> execute()){
          echo "Modification du rôle effectuée. ";
        }else{
          print $db->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> GreenRoad</title>
        <link rel="stylesheet" href="../CSS/adminusers.css?v=<?php echo time(); ?>"> 
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
                    </li><u><a href="../php/adminusers.php">Gestion utilisateurs</a></u></li>
                    </li><a href="../php/admincapteurs.php">Gestion capteurs</a></li>
                    </li><a href="../php/adminfaq.php">Gestion FAQ</a></li>
                </ul>
            </div>
        </nav>

        <form method="POST" action=""> 
            Rechercher un mot : <input type="text" name="recherche">
            <input type="SUBMIT" value="Search!"> 
        </form>

        <?php if ($search == 1  && ($_SESSION["role"] > $idRoleUser || $_SESSION["role"] == 3)) { ?>  
            <div class="userpage">
            <img src="../IMAGES/profillogo1.png"/>
            <h1 class="my-5"><b><?php echo $pseudo; ?></b></h1>
            <div id="informations">
                <table>
                    <tr>
                        <td class="info">
                            <table>
                                <h3><b><u>Informations personnelles</u></b></h3></br>
                                <p> Id utilisateur : <?php echo $idUser; ?> </br>
                                    Pseudo :  <?php echo $pseudo; ?> <a href="#id01"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a></br>
                                    E-mail : <?php echo $mail; ?> <a href="#id02"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a></br>
                                    Date d'inscription : <?php echo $dateInscription; ?> </br>
                                    <a href="#id03"><button>Modifier le mot de passe</button></a>
                                    <a href="#id04"><button>Supprimer le compte</button></a>
                                </p>
                            </table>
                        </td>
                        <td class="espace"></td>
                        <td class="info">
                            <table>
                                <h3><b><u>Pierrito</u></b></h3></br>
                                <p>Record : <?php echo $recordJeu." points "; ?> <a href="#id05"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a> </br>
                                    <blockquote>Le : <?php echo $dateRecord; ?> </blockquote></br>
                                Dernier score : <?php echo $lastScore." points "; ?> <a href="#id06"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a> </br>
                                    <blockquote>Le : <?php echo $dateLast; ?> </blockquote>
                                </p>
                            </table>
                        </td>
                        <td class="espace"></td>
                        <td class="info"> 
                            <table>
                                    <h3><b><u>Modération</u></b></h3></br>
                                    <p>Etat : <?php echo $etat; ?> <a href="#id07"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a> </br>
                                    <?php 
                                    if ($_SESSION["role"]==3) { ?>
                                         Role : <?php echo $role;?> <a href="#id08"><img src="../IMAGES/modify.png" style="width:20px; height:20px;"/></a> <?php } ?> </br>
                                    </p>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>





            <!--Les différents formulaires de modification-->

            <!--Formulaire 1 : modification du pseudo-->
            <div id="id01" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier le pseudo</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="pseudo">Entrez le nouveau pseudo</label><br/>
                            <input name="pseudo" type="text" id="pseudo"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp0">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp0" type="password" id="mdp0"/><br/><br/>
                        </div>  
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
                        <div class="form-group">
                            <input type="submit" class="confirmation1" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>  
                    </form>
                    <p> </br> <i> Attention ! L'utilisateur ne pourra plus se connecter avec son pseudo actuel suite à cette manipulation ! </i> </p>
                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 

            <!--Formulaire 2 : modification de l'adresse mail-->
            <div id="id02" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier l'adresse e-mail</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="email">Entrez la nouvelle adresse e-mail</label><br/>
                            <input name="email" type="text" id="email"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp" type="password" id="mdp"/><br/><br/>
                        </div>  
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
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

            <!--Formulaire 3 : modification du mot de passe-->
            <div id="id03" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier le mot de passe</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="mdp2">Entrez le nouveau mot de passe</label><br/>
                            <input name="mdp2" type="password" id="mdp2"/><br/>
                        </div>  
                        <div class="form-group">
                            <label for="mdp3">Confirmez le nouveau mot de passe</label><br/>
                            <input name="mdp3" type="password" id="mdp3"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp1">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp1" type="password" id="mdp1"/><br/><br/>
                        </div> 
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
                        <div class="form-group">
                            <input type="submit" class="confirmation2" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>  
                    </form>

                    <p> </br> <i> Attention ! L'utilisateur ne pourra plus se connecter avec son mot de passe actuel suite à cette manipulation ! </i> </p>

                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 

            <!--Formulaire 4 : suppression de compte-->
            <div id="id04" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Supprimer le compte</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="delete">Cochez pour <b>supprimer définitivement</b> le compte  </label>
                            <input name="delete" type="checkbox" id="delete"><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp4">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp4" type="password" id="mdp4"/><br/><br/>
                        </div>  
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
                        <div class="form-group">
                            <input type="submit" class="confirmation3" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>
                    </form>
                    <p> </br> <i> <b> Attention ! Le compte sera définitivement supprimé suite à cette manipulation </b> </i> </p>
                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 

            <!--Formulaire 5 : modification du record au Pierrito Game-->
            <div id="id05" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier le record au jeu Pierrito Game</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="record">Entrez le nouveau record</label><br/>
                            <input name="record" type="text" id="record"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp5">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp5" type="password" id="mdp5"/><br/><br/>
                        </div>  
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
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

            <!--Formulaire 6 : modification du dernier score au Pierrito Game-->
            <div id="id06" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier le dernier score au jeu Pierrito Game</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="last">Entrez le nouveau dernier score</label><br/>
                            <input name="last" type="text" id="last"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp6">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp6" type="password" id="mdp6"/><br/><br/>
                        </div> 
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>"> 
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

            <!--Formulaire 7 : modification de l'état (activé ou banni)-->
            <div id="id07" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier l'état de l'utilisateur</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="record">Sélectionnez le nouvel état de l'utilisateur</label><br/>
                            <input type="radio" name="etat" value="1"> Activé
                            <input type="radio" name="etat" value="2"> Banni <br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp7">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp7" type="password" id="mdp7"/><br/><br/>
                        </div>  
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
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
            <!-- $_POST[etat] ; -->


            <!--Formulaire 8 : modification du rôle (utilisateur, gestionnaire ou administrateur) (seul un admin peut modifier le rôle)-->
            <div id="id08" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier l'état de l'utilisateur</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="record">Sélectionnez le nouveau rôle de l'utilisateur</label><br/>
                            <input type="radio" name="role" value="1"> Utilisateur
                            <input type="radio" name="role" value="2"> Gestionnaire
                            <input type="radio" name="role" value="3"> Administrateur <br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp8">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp8" type="password" id="mdp8"/><br/><br/>
                        </div>  
                        <input id="id" name="id" type="hidden" value="<?php echo $idUser; ?>">
                        <div class="form-group">
                            <input type="submit" class="confirmation1" value="Valider">
                            <a href="#"><input type="submit" value="Annuler"></a>
                        </div>  
                    </form>
                    <p> </br> <i> <b> Attention ! Un grand pouvoir implique de grandes responsabilités ! Réfléchissez bien avant de modifier les droits d'accès d'un utilisateur ! </b> </i> </p>
                  </div>
                  <footer class="container">
                  </footer>
                </div>
              </div>
            </div> 
            <!-- $_POST[role] ; -->

            <h3 class="titreadmin">Dernières connexions de l'utilisateur</h3>
            <div class="adminbox">
                <p>
                    <?php 
                    foreach($conns as $conn){
                        echo $conn['dateCo']." : ".$pseudo." (".$conn['adresseIP'].") </br>";
                    }
                    ?>
                </p>
            </div>

















            <?php
        } ?>


        
    </body>
</html>
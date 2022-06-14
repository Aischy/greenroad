<?php

//Connection à la BDD, affiche les erreurs si il y a un problème

include_once('config.php');

$redirection=0;

//vérification du formulaire
if(!empty($_POST)){
    if(!strpos($_POST['email'],'@')){//sans @, ce n'est pas un email
        echo "email incorrect";
    } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE mail = '".$_POST['email']."'"))) {
    echo "Ce mail est déjà utilisé.";

    } elseif(empty($_POST['pseudo'])){ //pseudo vide => on arrête l'exécution du script
      echo "Le champ Pseudo est vide.";
    } elseif(!preg_match("#^[A-Za-z0-9]+$#",$_POST['pseudo'])){//pseudo contenant des caractères interdits
      echo "Le Pseudo doit être renseigné en lettres sans accents, sans caractères spéciaux.";
    } elseif(strlen($_POST['pseudo'])>32){//pseudo trop long
      echo "Le pseudo est trop long, il dépasse 32 caractères.";
    } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM users WHERE pseudo = '".$_POST['pseudo']."'"))) {
    echo "Ce pseudo est déjà utilisé.";

    } elseif(empty($_POST['mdp'])){//mot de passe vide
      echo "Le champ Mot de passe est vide.";
    } elseif(strlen($_POST['mdp'])>128){//mdp trop long
      echo "Le pseudo est trop long, il dépasse 128 caractères.";
    } elseif(strlen($_POST['mdp'])<8){//mdp trop court
      echo "Le pseudo est trop court, il doit faire au moins 8 caractères.";
    }elseif($_POST['mdp']!=$_POST['mdp1']){
        echo "Le mot de passe n'est pas le même";
    } else {

        //Insertion dans la BDD
        $pseudo = ($_POST['pseudo']);
        $email = ($_POST['email']);
        $mdp = md5($_POST['mdp']);
        $redirection=1;

        $stmt = $db->prepare("INSERT INTO users(pseudo,mail,password,dateInscription,recordJeu,lastScoreJeu,idRole,idEtat) VALUES(?, ?, ?, NOW(), 0, 0, 1, 1)");
        $stmt -> bind_param('sss', $pseudo, $email, $mdp);

        if($stmt -> execute()){
          echo "Bienvenue chez GreenRoad " . $pseudo . "!";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="../CSS/register.css?v=<?php echo time(); ?>">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>

    <?php if($redirection==1){
        echo "<meta http-equiv=\"refresh\" content=\"1; URL=mainpage.php\"/>";
    }
    ?>
</head>
<body>
    <nav>
        <a href="../php/MainPage.php">
            <img src="../IMAGES/GreenRoad.gif">
        </a>
        <h1>GreenRoad</h1>
    </nav>
    <div class="inscription">
        <h2>S'inscrire</h2>
        <p>Veuillez remplir ce formulaire pour créer un compte.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="pseudo">Nom d'utilisateur*</label><br/>
                <input name="pseudo" type="text" id="pseudo"/><br/>
            </div>
            <div class="form-group">
                <label for="email">Email*</label><br/>
                <input name="email" type="text" id="email"/><br/>
            </div>       
            <div class="form-group">
                <label for="mdp">Mot de Passe*</label><br/>
                <input name="mdp" type="password" id="mdp"/><br/>
            </div>
            <div class="form-group">
                <label for="mdp1">Répétez le mot de Passe*</label><br/>
                <input name="mdp1" type="password" id="mdp1"/><br/>
            </div>            

            <div class="form-group">
                <input type="submit" class="sinscrire" value="Soumettre">
                <input type="reset" class="reset" value="Réinitialiser">
            </div>
            <p>Vous avez déjà un compte? <a href="login.php">Connectez-vous ici</a>.</p>
        </form>
    </div>    
</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>

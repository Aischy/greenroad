<?php
$redirection=0;
// initialiser la session
session_start();
 
// Vérifiez si l'utilisateur est déjà connecté, si oui, puis redirige-le à la page UserPage
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $redirection=1;
    exit;
}
 
// Inclure le fichier config pour se connecter à la BDD
include_once('config.php');
 
// Définir des variables et initialiser avec des valeurs vides
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Traitement des données de formulaire lorsque le formulaire est soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Vérifiez si l'utilisateur est vide
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Vérifiez si le mot de passe est vide
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valider les informations d'identification
    if(empty($username_err) && empty($password_err)){
        // Préparer une instruction SELECT
        $sql = "SELECT idUser, pseudo, password, idRole FROM users WHERE pseudo = ?";
        
        if($stmt = mysqli_prepare($db, $sql)){
            // Lier les variables à l'instruction préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Définir des paramètres
            $param_username = $username;
            
            // Tenter d'exécuter la déclaration préparée
            if(mysqli_stmt_execute($stmt)){
                // Stocker le resultat
                mysqli_stmt_store_result($stmt);
                
                // Vérifiez si le nom d'utilisateur existe, si oui, vérifiez le mot de passe
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Assigner Résultat Variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $role);
                    if(mysqli_stmt_fetch($stmt)){
                        //Vérifier si le compte n'est pas banni ou en cours de suppression
                        if($role==1){
                            $login_err = "Vous n'êtes pas autorisé à vous connecter à l'espace administrateur.";
                        }elseif(md5($password)==$hashed_password){
                            // Le mot de passe est correct, alors commencez une nouvelle session
                            session_start();

                            
                            // Stocker les données dans les variables de session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username; 
                            $_SESSION["role"] = $role;                          
                            
                            // Rediriger l'utilisateur à la page utilisateur
                            $redirection=1;
                        } else{
                            // Le mot de passe n'est pas valide, affiche un message d'erreur générique
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Nom d'utilisateur n'existe pas, affiche un message d'erreur générique
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oups!Quelque chose s'est mal passé.Veuillez réessayer plus tard.";
            }

            // Déclaration de fermeture
            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($db);
}




// Obtenir l'adresse IP de l'utilisateur
include_once('config.php');


function getIp(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip1 = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip1 = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //}else if(!empty($_SERVER['REMOTE_ADDR'])){
        //$ip1 = $_SERVER['REMOTE_ADDR'];
    }else{
        $ip1 = "127.0.0.1";
    }
    return $ip1;
}
$ip=getIp();




// Enregistrement de la connexion dans les longs
$stmt = $db->prepare("INSERT INTO connexionsadmin(dateCo,adresseIP,idUser) VALUES(NOW(), ?, ?)");
$stmt -> bind_param('si', $ip, $id);

if($stmt -> execute()){
          echo "ok";
        }else{
          print $db->error;
        }

?>
 






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css?v=<?php echo time(); ?>">

    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
    <?php if($redirection==1){
        echo "<meta http-equiv=\"refresh\" content=\"1; URL=accueiladmin.php\"/>";
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
    <div class="connexion">
        <h2>Connexion administrateur</h2>
        <p>Attention ! Cette page est exclusivement réservée aux gestionnaires et au administrateur. </p>
        
        <?php 
        if(!empty($login_err)){
            echo '<div class="erreur"><div class="alert alert-danger">' . $login_err . '</div></div>';
        }        
        ?>
       
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nom d'utilisateur : </label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Mot de passe : </label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="connexion" value="Connexion">
            </div>
            <p>Vous n'avez pas de compte? <a href="register.php">S'inscrire maintenant</a>.</p>
        </form>
    </div>
</body>

<!--Footer-->
<?php include_once('footer.php'); ?>

</html>
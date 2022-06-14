<?php
// Initialiser la session
session_start();
 
// Vérifiez si l'utilisateur est connecté, sinon le redirige à la page de connexion
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"]==1){
    echo "<h1> Vous n'êtes pas autorisé à accéder à cette page ! </h1> ";
    echo "Veuillez retourner sur la <a href=\"../php/MainPage.php\">page d'accueil</a>. ";
    exit;
}

//------CONNEXION FAQ---------------

include_once('config.php');

//----------Récupération du mot de passe (crypté) de l'admin/gestionnaire----------
$idAdmin=$_SESSION["id"];
$user = $db->prepare("SELECT password FROM users WHERE idUser='$idAdmin' ");
$user->execute();
$user->bind_result($passwordAdmin);
while ($user->fetch()){}  

//------RECUPERATION DES QUESTIONS/REPONSES-----------
$stmt = $db->query("SELECT * FROM questions");
$faq = $stmt->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> GreenRoad</title>
        <link rel="stylesheet" href="../CSS/adminfaq.css?v=<?php echo time(); ?>"> 
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
                    </li><a href="../php/admincapteurs.php">Gestion capteurs</a></li>
                    </li><u><a href="../php/adminfaq.php">Gestion FAQ</a></u></li>
                </ul>
            </div>
        </nav>

        <section class='faq'>


        <!--Créer une nouvelle question-->

        <a href='#id00'><img src='../IMAGES/add.png' style='width:200px; height:60px; text-align: right; align-content:right ;margin-right:200px;'/></a>
        <br/><br/>
        

                <!--Affichage de la fenêtre modale-->
    <!--FORMULAIRE 0 : AJOUT D'UNE QUESTION/REPONSE A LA FAQ-->
        <div id="id00" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Ajouter une question</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="newquestion">Entrez la nouvelle question</label><br/>
                            <input name="newquestion" type="text" id="newquestion"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="newreponse">Entrez la réponse de la nouvelle question</label><br/>
                            <input name="newreponse" type="text" id="newreponse"/><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp0">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp0" type="password" id="mdp0"/><br/><br/>
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

                <!--Traitement du formulaire d'ajout de la question-->
<?php 
//FORMULAIRE 0 : AJOUT D'UNE QUESTION/REPONSE A LA FAQ
if(!empty($_POST['newquestion']) And !empty($_POST['newreponse']) And !empty($_POST['mdp0'])) {
    
    //VERIFICATION DU FORMULAIRE 1
    if(empty($_POST['newquestion'])){
      echo "Le champ Question est vide.";
    } elseif(strlen($_POST['newquestion'])>3000){
      echo "La question est trop longue, elle dépasse 3000 caractères.";
    } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM questions WHERE question = '".$_POST['newquestion']."'"))) {
    echo "Cette question existe déjà.";

    }else if(empty($_POST['newreponse'])){
      echo "Le champ Réponse est vide.";
    } elseif(strlen($_POST['newreponse'])>3000){
      echo "La reponse est trop longue, elle dépasse 3000 caractères.";
    } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM questions WHERE reponse = '".$_POST['newreponse']."'"))) {
    echo "Cette reponse existe déjà.";

    }elseif(md5($_POST['mdp0'])!=$passwordAdmin){
        echo "Mot de passe incorrect";

    }else{
        //EXECUTION DU FORMULAIRE 1
        $newquestion=$_POST['newquestion'];
        $newreponse=$_POST['newreponse'];

        $stmt = $db->prepare("INSERT INTO questions(question,reponse) VALUES(?, ?)");
        $stmt -> bind_param('ss', $newquestion, $newreponse);

        if($stmt -> execute()){
          echo "Ajout de la question effectuée. ";
        }else{
          print $db->error;
        }
    }
}










?>


        <h2> <u>Modifier / supprimer une question</u> </h2>
        <br/>

        <!--Gérer les questions/réponses existantes-->

        <section class="faq">
        <?php
        $i=0;


        foreach ($faq as $sentence){
            //Affichage des questions
            
            echo '
                <button class="collapsible">'.$sentence['question'].'
                    <a href="#id01'.$i.'">
                        <img src="../IMAGES/modify.png" style="width:20px; height:20px;"/>
                    </a> 
                    <a href="#id02'.$i.'">
                        <img src="../IMAGES/delete.png" style="width:20px; height:20px;"/>
                    </a>
                </button>
                <div class="contentcollaps">
                    <p>'.$sentence['reponse'].'
                        <a href="#id03'.$i.'">
                            <img src="../IMAGES/modify.png" style="width:20px; height:20px;"/>
                        </a> 
                    </p>
                </div>
                <br/><br/>
            ';


            //Affichage des fenêtres modales
            echo '
            <!--Formulaire 1 : modification de la question-->
            <div id="id01'.$i.'" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier la question</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                        <div class="form-group">
                            <label for="question">Modifiez la question</label><br/>
                            <div class="fcf-input-group">
                                    <input name="question" id="question" value="'.$faq[$i]['question'].'"/>
                                    <br/><br/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mdp1">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp1" type="password" id="mdp1"/><br/><br/>
                        </div>  
                        <input id="id1" name="id1" type="hidden" value="'.$faq[$i]['idQuestion'].'">
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

            <!--Formulaire 2 : modification de la réponse-->
            <div id="id03'.$i.'" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Modifier la réponse</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                        <div class="form-group">
                            <label for="reponse">Modifiez la réponse</label><br/>
                            <div class="fcf-input-group">
                                    <input name="reponse" id="reponse" value="'.$faq[$i]['reponse'].'"/>
                                    <br/><br/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="mdp2">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp2" type="password" id="mdp2"/><br/><br/>
                        </div>  
                        <input id="id2" name="id2" type="hidden" value="'.$faq[$i]['idQuestion'].'">
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

            <!--Formulaire 3 : suppression de la question/réponse-->
            <div id="id02'.$i.'" class="modal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <header class="container"> 
                    <a href="#" class="closebtn">×</a>
                    <h2>Supprimer la question</h2>
                  </header>
                  <div class="container" id="body">
                    <form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                        <div class="form-group">
                            <label for="delete">Cochez pour <b>supprimer définitivement</b> la question  </label>
                            <input name="delete" type="checkbox" id="delete"><br/><br/>
                        </div>
                        <div class="form-group">
                            <label for="mdp3">Entrez votre mot de passe pour confirmer</label><br/>
                            <input name="mdp3" type="password" id="mdp3"/><br/><br/>
                        </div>
                        <input id="id3" name="id3" type="hidden" value="'.$faq[$i]['idQuestion'].'">
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
            </div>';
            

            

            //Traitement des formulaires

                    //FORMULAIRE 1 : MODIFICATION DE LA QUESTION
                    if(!empty($_POST['question']) And !empty($_POST['mdp1'])) {
                        //VERIFICATION DU FORMULAIRE 1
                        if(empty($_POST['question'])){ //pseudo vide => on arrête l'exécution du script
                          echo "Le champ Question est vide.";
                        } elseif(strlen($_POST['question'])>3000){//pseudo trop long
                          echo "La question est trop longue, elle dépasse 3000 caractères.";
                        } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM questions WHERE question = '".$_POST['question']."'"))) {
                        echo "Cette question existe déjà.";
                        }else if($_POST['question'] == $faq[$i]['question']){
                            echo "La question n'a pas été modifiée. ";
                        }elseif(md5($_POST['mdp1'])!=$passwordAdmin){
                            echo "Mot de passe incorrect";
                        }else{
                            //EXECUTION DU FORMULAIRE 1
                            $newquestion=$_POST['question'];
                            $id = $_POST['id1'];
                            $stmt = $db->prepare("UPDATE questions SET question='$newquestion' WHERE idQuestion='$id'");
                            if($stmt -> execute()){
                              echo "Modification de la question effectuée. ";
                            }else{
                              print $db->error;
                            }
                        }

                    //FORMULAIRE 2 : MODIFICATION DE LA REPONSE
                    }else if(!empty($_POST['reponse']) And !empty($_POST['mdp2'])) {
                        //VERIFICATION DU FORMULAIRE 2
                        if(empty($_POST['reponse'])){ //pseudo vide => on arrête l'exécution du script
                          echo "Le champ Réponse est vide.";
                        } elseif(strlen($_POST['reponse'])>3000){//pseudo trop long
                          echo "La reponse est trop longue, elle dépasse 3000 caractères.";
                        } elseif(mysqli_num_rows(mysqli_query($db, "SELECT * FROM questions WHERE reponse = '".$_POST['reponse']."'"))) {
                        echo "Cette reponse existe déjà.";
                        }else if($_POST['reponse'] == $faq[$i]['reponse']){
                            echo "La reponse n'a pas été modifiée. ";
                        }elseif(md5($_POST['mdp2'])!=$passwordAdmin){
                            echo "Mot de passe incorrect";
                        }else{
                            //EXECUTION DU FORMULAIRE 2
                            $newreponse=$_POST['reponse'];
                            $id = $_POST['id2'];
                            $stmt = $db->prepare("UPDATE questions SET reponse='$newreponse' WHERE idQuestion='$id'");
                            if($stmt -> execute()){
                              echo "Modification de la réponse effectuée. ";
                            }else{
                              print $db->error;
                            }
                        }
                    //FORMULAIRE 3 : SUPPRESSION DEFINITIVE DE LA QUESTION
                    }elseif(!empty($_POST['delete']) And !empty($_POST['mdp3'])) {
                        //VERIFICATION DU FORMULAIRE 3
                        if(md5($_POST['mdp3'])!=$passwordAdmin){
                            echo "Mot de passe incorrect";
                        }else{
                            //EXECUTION DU FORMULAIRE 3
                            $id = $_POST['id3'];;
                            $deleteQuestion = $db->prepare("DELETE FROM questions WHERE idQuestion='$id'");
                            if($deleteQuestion -> execute()){
                              echo "Question supprimée. ";
                            }else{
                              print $db->error;
                            }

                        }
                    }
                $i++;
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
</section>




        
    </body>
</html>
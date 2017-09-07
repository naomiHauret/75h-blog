<?php
  include_once("models/database_connexion.php");
  include_once("models/users/index.php");
  include_once("helpers/helpers.php");

  function connexion(){
    global $message;

    if(isset($_POST["user_mail"]) && isNotEmpty(cleanString($_POST["user_mail"])) && isset($_POST["user_password"]) && isNotEmpty(cleanString($_POST["user_password"]))) {
      $user_mail= cleanString($_POST["user_mail"]);
      $user_password= cleanString($_POST["user_password"]);

      $result= is_indatabase($user_mail, $user_password);
      if (!$result){
          $message= "Erreur dans votre identifiant et/ou votre mot de passe." ;
      }

      else{
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['user_mail'] = $user_mail;
        
        if( $result['user_is_admin'] == true){
          $_SESSION['is_admin'] = true;
        }

        $message= "Bienvenue!";
      }
    }

    else{
      $message= "Erreur: email et/ou mot de passe manquants.";
    }
  }

  function deconnexion(){
    session_destroy();
    $_SESSION = array();
    if(isset($_SESSION["user_id"])){
      $message= "Erreur: vous n'avez pas été déconnecté. Veuillez réessayer.";
    }
    else{
      $message= "Vous êtes bien déconnecté.";
    }
  }
?>

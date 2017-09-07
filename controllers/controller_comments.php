<?php
  include_once("models/database_connexion.php");
  include_once("models/comments/index.php");
  include_once("helpers/helpers.php");

  function controller_get_comment(){
    global $message;
    global $title;

    if(isset($_GET["id"])){
      $comment_id = $_GET["id"];
    }

    $comment= model_get_comment($comment_id);

    return $comment;
  }

  function controller_get_article_comments(){
    if(isset($_SESSION["article_id"])){
      $article_id=$_SESSION["article_id"];    }
    else {
      $article_id=$_GET["id"];
    }
    
    $comments= model_get_article_comments($article_id);

    for($i=0; $i< sizeof($comments); $i++){
      $comments[$i]['comment_date']= date("d/m/Y H:i", strtotime($comments[$i]['comment_date']));
    }
    return $comments;
  }


  function controller_delete_comment(){
    global $message;
    global $title;

    if(isset($_SESSION["is_admin"]) || isset($_SESSION["user_id"])){
      $comment_id=$_GET["id"];
      $result= model_delete_comment($comment_id);

      if (!$result){
          $title="Suppression du commentaire impossible!";
          $message="Le commentaire  n'a pas pu être supprimé.";
          $_SESSION['error']=true;
          unset($_SESSION['success']);
          unset($_SESSION['warning']);
      }
      else {
        $title="Commentaire supprimé!";
        $message="Le commentaire a été supprimée.";
        $_SESSION['success']=true;
        unset($_SESSION['error']);
        unset($_SESSION['warning']);
      }
    }
    else {
      $title= "Action non autorisée!";
      $message="Vous n'êtes pas autorisé à effectuer cette action.";
      $result=-1;

    }
  }

  function controller_add_comment(){
    global $message;
    global $title;

    if( isset($_SESSION["article_id"]) && isNotEmpty($_SESSION["article_id"]) &&  isset( $_POST["comment_content"]) && isNotEmpty( $_POST["comment_content"]) ){
      $article_id= $_SESSION["article_id"];
      $comment_content= $_POST["comment_content"];
      isset($_SESSION["user_id"]) == true ? $comment_authorid = $_SESSION["user_id"] : $comment_authorid = NULL;

      $result= model_add_comment($article_id, $comment_content, $comment_authorid);

      if (!$result){
        $result=-1;
        $title="Ajout du commentaire impossible!";
        $message="Le commentaire n'a pas pu être ajouté.";
        $_SESSION['error']=true;
        unset($_SESSION['success']);
      }
      else {
        $title="Ajout du commentaire réussi!";
        $message="Le commentaire a bien été ajouté.";
        $_SESSION['success']=true;
        unset($_SESSION['error']);
        unset($_SESSION['warning']);
      }
    }
    else {
      $title="Ajout du commentaire impossible!";
      $message="Le commentaire n'a pas pu être ajouté car certains champs sont manquants et/ou invalides.";
      $result=-1;
      $_SESSION['warning']=true;
      unset($_SESSION['error']);
      unset($_SESSION['success']);
    }
    return $result;
  }

?>

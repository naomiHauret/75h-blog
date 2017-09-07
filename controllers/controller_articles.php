<?php
  include_once("models/database_connexion.php");
  include_once("models/articles/index.php");
  include_once("helpers/helpers.php");

  function controller_get_articles(){
    $articles= model_get_articles();

    for($i=0; $i< sizeof($articles); $i++){
      $articles[$i]['']= substr($articles[$i]['article_intro'], 0, 120)."...";
      $articles[$i]['article_publicationdate']= date("d/m/Y", strtotime($articles[$i]['article_publicationdate']));

      if($articles[$i]['article_updatedate']==''){
        $articles[$i]['article_updatedate']= "-";
      }
      else{
        $articles[$i]['article_publicationdate']= date("d/m/Y", strtotime($articles[$i]['article_publicationdate']));
      }
    }
    return $articles;
  }


  function controller_get_article(){
    global $message;
    global $title;
    if(isset($_SESSION["article_id"])){
      $article_id=$_SESSION["article_id"];
    }
    else{
      $article_id=$_GET["id"];
    }

    $article= model_get_article($article_id);

    if(!$article) {
        $title="Cet article n'existe pas!";
        $message="Nous sommes désolés mais cet article n'existe pas. Il a peut-être été supprimé.";
        $_SESSION['warning']=true;
        unset($_SESSION['error']);
        unset($_SESSION['success']);
    }
    else {
      unset($_SESSION['success']);
      unset($_SESSION['error']);
      unset($_SESSION['warning']);

      $article['article_publicationdate']= date("d/m/Y H:i", strtotime($article['article_publicationdate']));
      $article['article_tagslist']=  explode(";", $article['article_tagslist']);

    }
      return $article;
  }

    function controller_get_author_articles(){
    global $message;
    global $title;
    if(isset($_SESSION["user_id"])) {
      $user_id= $_SESSION["user_id"];
      $articles= model_get_author_articles($user_id);
      for($i=0; $i< sizeof($articles); $i++){
        $articles[$i]['article_publicationdate']= date("d/m/Y", strtotime($articles[$i]['article_publicationdate']));

        if($articles[$i]['article_updatedate']==''){
          $articles[$i]['article_updatedate']= "-";
        }
        else{
          $articles[$i]['article_publicationdate']= date("d/m/Y", strtotime($articles[$i]['article_publicationdate']));
        }
      }
    }
    else{
      unset($_SESSION['success']);
      unset($_SESSION['error']);
      $title= "Action non autorisée!";
      $message="Vous n'êtes pas autorisé à effectuer cette action.";
      $articles= -1;
    }
      return $articles;
  }

  function controller_delete_article(){
    global $message;
    global $title;

    if(isset($_GET["id"]) && isset($_SESSION["user_id"]) ){
    $article_id=$_GET["id"];
    if(isset($_SESSION["is_admin"]) || isOwner($_SESSION["user_id"], $article_id)){
      $result= model_delete_article($article_id);

      if (!$result){
          $title="Suppression de l'article impossible!";
          $message="L'article  n'a pas pu être supprimé.";
          $_SESSION['error']=true;
          unset($_SESSION['success']);
          unset($_SESSION['warning']);
      }
      else{
        $title="Article supprimé!";
        $message="L'article et tous ses commentaires ont été supprimés.";
        $_SESSION['success']=true;
        unset($_SESSION['error']);
        unset($_SESSION['warning']);
      }
    }
    else{
      $_SESSION['warning']=true;
      unset($_SESSION['success']);
      unset($_SESSION['error']);
      $title= "Action non autorisée!";
      $message="Vous n'êtes pas autorisé à effectuer cette action.";
    }
  }
  else{
    $_SESSION['error']=true;
    unset($_SESSION['success']);
    unset($_SESSION['warning']);
    $title= "Suppression de l'article impossible impossible!";
    $message="L'article n'a pu être supprimé.";
  }
}

  function controller_add_article(){
    global $message;
    global $title;
    global $article_coverpath;

    if(isset($_SESSION["user_id"])) { //check if user is logged in

      if(isset($_POST["article_title"]) && isNotEmpty($_POST["article_title"]) && isset($_POST["article_intro"]) && isNotEmpty($_POST["article_intro"]) &&  isset($_POST["article_content"]) && isNotEmpty($_POST["article_content"]) && isset($_POST["article_tagslist"]) && isNotEmpty($_POST["article_tagslist"])) { // check if all required fields are filled
        // clean values
        $article_title = cleanString($_POST["article_title"]);
        $article_intro = cleanString($_POST["article_intro"]);
        $article_content= cleanString($_POST["article_content"]);
        $article_tagslist= cleanString($_POST["article_tagslist"]);
        
        $article_authorid= $_SESSION["user_id"]; //get author id

        if(isset($_FILES["article_cover"]) && is_uploaded_file($_FILES["article_cover"]["tmp_name"]) ) { // check if user did send a cover picture or not
          $article_coverpath= controller_upload_picture();

          if($article_coverpath <= -1 ) { // check if cover is not too big/wrong format
            $result=-1;
            $title="Photo de couverture non conforme!";
            $message="Votre photo de couverture doit avoir un poids inférieur à 2MB et avoir l'un des formats suivants: JPEG, JPG, PNG, SVG.";
            $_SESSION['error']=true;
            unset($_SESSION['success']);
          }
          else { // we're all good
            $result= model_add_article($article_title, $article_intro, $article_content, $article_tagslist, $article_coverpath, $article_authorid); // add article to database
          }
        }
        else { // no cover picture sent
          $article_coverpath= NULL;
          $result= model_add_article($article_title, $article_intro, $article_content, $article_tagslist, $article_coverpath, $article_authorid); // add article to database (placeholder picture will be used)
        }

        if(!$result || $result == -1) { // fail to add to database
          $result=-1;
          $title="Publication de l'article impossible!";
          $message="L'article n'a pas pu être publié.";
          $_SESSION['error']=true;
          unset($_SESSION['success']);
        }
        else {
          $title="Publication de l'article réussi!"; // success
          $message="L'article a bien été publié.";
          $_SESSION['success']=true;
          unset($_SESSION['error']);
          unset($_SESSION['warning']);
        }
      }
      else { // missing or invalid fields
        $title="Publication de l'article impossible!";
        $message="L'article n'a pas pu être publié car certains champs sont manquants et/ou invalides.";
        $result=-1;
        $_SESSION['warning']=true;
        unset($_SESSION['error']);
        unset($_SESSION['success']);
      }
    }
    else { // user not logged in -> error 403
      $title= "Action non autorisée!";
      $message="Vous n'êtes pas autorisé à effectuer cette action.";
      $_SESSION['warning']=true;
      unset($_SESSION['error']);
      unset($_SESSION['success']);
    }
    
    return $result;
  }

  function controller_edit_article(){
    global $title;
    global $message;

    if(isset($_SESSION["article_id"]) && isNotEmpty($_SESSION["article_id"]) && isset($_SESSION["user_id"])) { // check if user is logged in
      $article_id= $_SESSION["article_id"];

      if(isset($_SESSION["is_admin"]) || isOwner($_SESSION["user_id"], $article_id) ){ // check if user is admin or created the article
         if(isset($_POST["article_title"]) && isNotEmpty($_POST["article_title"]) && isset($_POST["article_intro"]) && isNotEmpty($_POST["article_intro"]) &&  isset($_POST["article_content"]) && isNotEmpty($_POST["article_content"]) && isset($_POST["article_tagslist"]) && isNotEmpty($_POST["article_tagslist"])) { // check if all required fields are filled
          $article_title = cleanString($_POST["article_title"]);
          $article_intro = cleanString($_POST["article_intro"]);
          $article_content= cleanString($_POST["article_content"]);
          $article_tagslist= cleanString($_POST["article_tagslist"]);

          if(isset($_FILES["article_cover"]) && is_uploaded_file($_FILES["article_cover"]["tmp_name"]) ) { // check if user did send a cover picture or not
            $article_coverpath= controller_upload_picture();

            if($article_coverpath <= -1 ) {
              $result=-1;
              $title="Photo de couverture non conforme!";
              $message="Votre photo de couverture doit avoir un poids inférieur à 2MB et avoir l'un des formats suivants: JPEG, JPG, PNG, SVG.";
              $_SESSION['error']=true;
              unset($_SESSION['success']);
            }
            else {
              $result= model_edit_article($article_id, $article_title, $article_intro, $article_content, $article_tagslist, $article_coverpath); // add article to database
            }
          }
        else {
          if($article_coverpath  > -1) {
            $article_coverpath= NULL;
            $result= model_edit_article($article_id, $article_title, $article_intro, $article_content, $article_tagslist, $article_coverpath); // add article to database
          }
          else {
            $result = -1;
          }
        }
          
          if (!$result) { // error
            $result=-1;
            $title="Modification de l'article impossible!";
            $message="L'article n'a pas pu être modifié.";
            $_SESSION['error']=true;
            unset($_SESSION['success']);
          }
          else { // success
            $title="Modification de l'article réussi!";
            $message="L'article été modifié.";
            $_SESSION['success']=true;
            unset($_SESSION['error']);
            unset($_SESSION['warning']);
          }
        }
        else { // error: missing/invalid fields
          $title="Modification de l'article impossible!";
          $message="L'article n'a pas pu être modifié car certains champs sont manquants et/ou invalides.";
          $result=-1;
          $_SESSION['warning']=true;
          unset($_SESSION['error']);
          unset($_SESSION['success']);
        }
      }
    }
    else { // user not logged in
      $title= "Action non autorisée!";
      $message="Vous n'êtes pas autorisé à effectuer cette action.";
      $_SESSION['warning']=true;
      unset($_SESSION['error']);
      unset($_SESSION['success']);
      $result= -1;
    }

    return $result;
  }

  function controller_upload_picture() {
    $uploads_dir = "dist/assets/img/covers/"; //same, but variable

    $file_name= $_FILES['article_cover']['name'];
    $file_size= $_FILES['article_cover']['size'];
    $file_tmp= $_FILES['article_cover']['tmp_name'];
    $file_type= $_FILES['article_cover']['type'];
    $tmp = explode('.', $file_name);
    $file_ext = end($tmp);
    $errors= [];
    $expensions= array("jpeg","jpg","png", "svg");

    if(in_array(strtolower($file_ext), $expensions)=== false){
       $errors[]= "Erreur: votre photo de couverture doit être au format JPG/JPEG, PNG ou SVG.";
    }

    if($file_size > 2097152) {
       $errors[]= 'Erreur: votre photo de couverture doit peser moins de 2 MB.';
    }

    if(empty($errors)==true) {
      $target_file = $uploads_dir.basename($_FILES["article_cover"]["name"]); //where we want to store our file

      if (move_uploaded_file($file_tmp, $target_file)) {
          $action_message="Le fichier ". basename($_FILES["article_cover"]["name"]). " a bien été téléchargé.";
          $coverpath= $uploads_dir.$_FILES['article_cover']['name'];
      } else {
          $action_message="Désolé, votre fichier n'a pas pu être téléchargé. Veuillez recommencer.";
          $coverpath= -1;

      }
    }
    else {
      $coverpath= -1;
    }
   return $coverpath;
  }

  function isOwner($user_id, $article_id) { // check if user is the original writer of an article
    $result = false;
    $article= model_get_article($article_id);
    if($article["article_authorid"] == $user_id){
      $result=true;
    }
    return $result;
  }

?>

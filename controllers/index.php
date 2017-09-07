<?php
//Twig config
require 'vendor/autoload.php';
$assetsPath = "./dist/assets/img/";
$placeholderCoverPath = "./dist/assets/img/covers/placeholder.png";

$viewsFolders = array('views', 'views/templates', 'views/includes', 'views/includes/layout', 'views/includes/forms', 'views/includes/ui-bricks', 'views/pages', 'views/macros');
$loader = new Twig_Loader_Filesystem($viewsFolders);
$twig = new Twig_Environment($loader, array(
  'cache' => false
));
$twig->addGlobal('assetsPath', $assetsPath);
$twig->addGlobal('placeholderCoverPath', $placeholderCoverPath);

session_start();
//////////////////////////////////////////////////////////////////////////////////
//GET
//////////////////////////////////////////////////////////////////////////////////
if(!empty($_GET["action"])){
  $action=  $_GET["action"];

  unset($_SESSION['error']);
  unset($_SESSION['success']);
  unset($_SESSION['warning']);

  switch($action){
    // login
    case "login":
      $twig->addGlobal('session', $_SESSION);
      if(isset($_SESSION["user_id"])){
        include_once("controllers/controller_articles.php");
        $articles= controller_get_articles();
        echo $twig->render('articles.twig', array('articles' => $articles));
      }
      else{
        echo $twig->render('connexion.twig');
      }
    break;

    // logout
    case "logout":
      include_once("controllers/controller_users.php");
      deconnexion();
      $twig->addGlobal('session', $_SESSION);
      include_once("controllers/controller_articles.php");
      $articles= controller_get_articles();
      echo $twig->render('articles.twig', array('articles' => $articles));
     
    break;

    //get
    case "get-articles": //récupérer tous les articles
      unset($_SESSION['article_id']);
      include_once("controllers/controller_articles.php");
      $articles= controller_get_articles();
      $twig->addGlobal('session', $_SESSION);
      echo $twig->render('articles.twig', array('articles' => $articles));
    break;

    case "get-article": // get a single article with given id
        include_once("controllers/controller_articles.php");
        include_once("controllers/controller_comments.php");
        $article= controller_get_article();
        if($article == ""){
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error404.twig');
        }
        else{
          $comments= controller_get_article_comments();
          $_SESSION["article_id"]= $article["article_id"];
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('article.twig', array('article' => $article, 'comments' => $comments));
        }
    break;

    case "dashboard": // get all articles of current logged in user
      if(isset($_SESSION["user_id"])){
          unset($_SESSION['article_id']);
          include_once("controllers/controller_articles.php");
          $articles= controller_get_articles();
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('dashboard.twig', array('articles' => $articles));
        }
        else{
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error403.twig');
        }
    break;

    case "my-articles": // get all articles of current logged in user
      if(isset($_SESSION["user_id"])){
          include_once("controllers/controller_articles.php");
          $articles= controller_get_author_articles();
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('dashboard.twig', array('articles' => $articles));
        }
        else{
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error403.twig');
        }
    break;

    //add
    case "add-article": // add an article
      unset($_SESSION['article_id']);
      if(isset($_SESSION["user_id"])){
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('new-article.twig');
      }
      else{
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('error403.twig');
      }
    break;

    //update
    case "update-article":
      unset($_SESSION['article_id']);
      include_once("controllers/controller_articles.php");
      $article= controller_get_article();
      if($article == ""){
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('error404.twig');
      }
      else{
        if(isset($_SESSION["user_id"]) && ( isset($_SESSION["is_admin"]) || $article['article_authorid'] == $_SESSION["user_id"]) ){
          $_SESSION["article_id"]= $article["article_id"];
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('update-article.twig', array('article' => $article));
        }
        else{
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error403.twig');
        }
      }
    break;

    //delete
    case "delete-article":
      include_once("controllers/controller_articles.php");
      $article= controller_get_article();
      if($article == ""){
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('error404.twig');
      }
      else{
        if(isset($_SESSION["user_id"]) && ($article['article_authorid'] == $_SESSION["user_id"] || isset($_SESSION["is_admin"])) ){
          controller_delete_article();
          $articles= controller_get_articles();
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('articles.twig', array('articles' => $articles));
        }
        else{
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error403.twig');
        }
      }
    break;

    case "delete-comment":
      include_once("controllers/controller_comments.php");
      include_once("controllers/controller_articles.php");

      $comment= controller_get_comment();
      if($comment == ""){
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('error404.twig');
      }
      else{
        if(isset($_SESSION["user_id"]) &&  (isset($_SESSION["is_admin"]) || $comment['comment_authorid'] == $_SESSION["user_id"] ) ){
          controller_delete_comment(); // delete given comment

          $article= controller_get_article(); // get back the article
          $comments= controller_get_article_comments(); // get the article comments list
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('article.twig', array('article' => $article,  'comments' => $comments ));
        }
        else{
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error403.twig');
        }
      }
    break;

    // Other action
    default:
      $twig->addGlobal('session', $_SESSION);
      echo $twig->render('error403.twig');
    break;
  }
}
//////////////////////////////////////////////////////////////////////////////////
//POST
//////////////////////////////////////////////////////////////////////////////////
else{
  if(!empty($_POST['submit_connexion'])) {
    include_once("controllers/controller_users.php");
    connexion();
    $twig->addGlobal('session', $_SESSION);

    if(isset($_SESSION["user_id"])){
      $twig->addGlobal('session', $_SESSION);

      include_once("controllers/controller_articles.php");
      $articles= controller_get_articles();
      echo $twig->render('articles.twig', array('articles' => $articles));
    }
    else{
      echo $twig->render('connexion.twig');
    }
  }
  else if(!empty($_POST['submit_article'])){
    if(isset($_SESSION["user_id"])){
      include_once("controllers/controller_articles.php");
      $success= controller_add_article();

      if($success == -1) {
       if(isset($_SESSION["user_id"])){
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('new-article.twig');
        }
        else{
          $twig->addGlobal('session', $_SESSION);
          echo $twig->render('error403.twig');
        }
      }
      else {
        $articles= controller_get_articles();
        $twig->addGlobal('session', $_SESSION);
        echo $twig->render('articles.twig', array('articles' => $articles));
      }
    }
    else{
      $twig->addGlobal('session', $_SESSION);
      echo $twig->render('error403.twig');
    }
    unset($_POST['submit_article']);
  }

  else if(!empty($_POST['edit_article'])){
    include_once("controllers/controller_articles.php");
    $article= controller_get_article();

    if(isset($_SESSION["user_id"]) || $article['article_authorid'] == $_SESSION["user_id"]){
      include_once("controllers/controller_comments.php");
      controller_edit_article();
      $article= controller_get_article();
      $comments= controller_get_article_comments();
      $twig->addGlobal('session', $_SESSION);
      echo $twig->render('article.twig', array('article' => $article, 'comments' => $comments));
    }
    else{
      $twig->addGlobal('session', $_SESSION);
      echo $twig->render('error403.twig');
    }
    unset($_POST['submit_article']);
  }

  else if(!empty($_POST['submit_comment'])) { // comment added

    include_once("controllers/controller_comments.php");
    include_once("controllers/controller_articles.php");
    controller_add_comment();
    $article= controller_get_article();
    $comments= controller_get_article_comments();
    $twig->addGlobal('session', $_SESSION);
    echo $twig->render('article.twig', array('article' => $article, 'comments' => $comments));

    unset($_POST['submit_comment']);
  }

  //////////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////////
  else{
    unset($_SESSION['error']);
    unset($_SESSION['success']);
    unset($_SESSION['warning']);
    unset($_SESSION['article_id']);

    include_once("controllers/controller_articles.php");
    $articles= controller_get_articles();
    $twig->addGlobal('session', $_SESSION);
    echo $twig->render('articles.twig', array('articles' => $articles));
  }
}

?>

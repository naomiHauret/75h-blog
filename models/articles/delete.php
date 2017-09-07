<?php
function model_delete_article($article_id){
  global $db;

  // Delete all related comments 
  $query = $db->prepare('DELETE FROM comments WHERE comments.comment_articleid = :comment_articleid');
  $result= $query->execute(array(
    'comment_articleid' => $article_id
  ));

  // Delete article
  if($result){
    $query = $db->prepare('DELETE FROM articles WHERE articles.article_id = :article_id');
    $result= $query->execute(array(
      'article_id' => $article_id
    ));

    if($result){
     return $result;
   }
   else{
     echo "Erreur: impossible de supprimer l'article. Veuillez réessayer.";
   }
 }

}
?>

<?php
function model_delete_comment($comment_id){
  global $db;

  $query = $db->prepare('DELETE FROM comments WHERE comments.comment_id = :comment_id');
  $result= $query->execute(array(
    'comment_id' => $comment_id
  ));

  if($result){
   return $result;
 }
 else{
   echo "Erreur: impossible de supprimer le commentaire. Veuillez réessayer.";
 }

}
?>

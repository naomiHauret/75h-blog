<?php
  function model_add_comment($article_id, $comment_content, $comment_authorid){
    global $db;

    $query = $db->prepare('INSERT INTO comments(
      comment_articleid, comment_content, comment_authorid )
      VALUES(:comment_articleid, :comment_content, :comment_authorid)'
    );

    $result= $query->execute(array(
      'comment_articleid' => $article_id,
      'comment_content' => $comment_content,
      'comment_authorid' => $comment_authorid
    ));


    if($result){
     $last_id = $db->lastInsertId();
     return $last_id;
   }
   else{
     echo "erreur";
   }
  }
?>

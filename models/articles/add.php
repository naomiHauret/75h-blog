<?php
  function model_add_article($article_title, $article_intro, $article_content, $article_tagslist, $article_coverpath, $article_authorid){
    global $db;

    $query = $db->prepare('INSERT INTO articles (
      article_title, article_intro, article_content, article_tagslist, article_coverpath, article_authorid)
      VALUES(:article_title, :article_intro, :article_content, :article_tagslist, :article_coverpath, :article_authorid)'
    );

    $result= $query->execute(array(
      'article_title' => $article_title,
      'article_intro' => $article_intro,
      'article_content' => $article_content,
      'article_tagslist' => $article_tagslist,
      'article_coverpath' => $article_coverpath,
      'article_authorid' => $article_authorid
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

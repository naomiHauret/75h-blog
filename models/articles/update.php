<?php
function model_edit_article($article_id, $article_title, $article_intro, $article_content, $article_tagslist, $article_coverpath){
  global $db;
  $query=  $db->prepare(
    'UPDATE articles 
    SET article_title = :article_title, article_intro = :article_intro, article_content = :article_content, article_tagslist = :article_tagslist,  article_coverpath = :article_coverpath, article_updatedate = NOW()    
    WHERE article_id = :article_id ');
  $result= $query->execute(array(
    'article_title' => $article_title,
    'article_intro' => $article_intro,
    'article_content' => $article_content,
    'article_tagslist' => $article_tagslist,
    'article_coverpath' => $article_coverpath,
    'article_id' => $article_id
  ));

  if($result){
    echo $result;
    return $result;
   }
   else{
     echo "Erreur: impossible de modifier l'article. Veuillez réessayer.";
   }
}

?>

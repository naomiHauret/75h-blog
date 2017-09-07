<?php
  function model_get_articles(){
    global $db;
    $query = $db->prepare('
      SELECT articles.article_id, articles.article_authorid, articles.article_title, articles.article_intro, articles.article_publicationdate, articles.article_updatedate, articles.article_coverpath, users.user_firstname, users.user_lastname
      FROM articles INNER JOIN users
      ON articles.article_authorid = users.user_id
      ORDER BY articles.article_publicationdate DESC
    ');
    $query->execute();
    $result = $query->fetchAll();
    return $result;
  }

  function model_get_author_articles($user_id){
    global $db;
    $query = $db->prepare('
      SELECT articles.article_id, articles.article_title, articles.article_publicationdate, articles.article_updatedate
      FROM articles INNER JOIN users ON articles.article_authorid = users.user_id 
      WHERE articles.article_authorid = :article_authorid
      ORDER BY articles.article_publicationdate DESC
    ');
    $query->execute(array(
      'article_authorid' => $user_id
    ));
    $result = $query->fetchAll();
    return $result;
  }
  function model_get_article($article_id){
    global $db;
    $query = $db->prepare('
      SELECT articles.article_id, articles.article_authorid, articles.article_title, articles.article_intro, articles.article_publicationdate, articles.article_updatedate, articles.article_coverpath, articles.article_tagslist, articles.article_content, users.user_firstname, users.user_lastname
      FROM articles INNER JOIN users
      ON articles.article_authorid = users.user_id
      WHERE articles.article_id = :article_id
    ');
    $query->execute(array(
      'article_id' => $article_id
    ));
    $result = $query->fetch();
    return $result;
  }

?>

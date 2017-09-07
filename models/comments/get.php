<?php
  function model_get_comment($comment_id){
    global $db;
    $query = $db->prepare('
      SELECT *
      FROM comments
      WHERE comments.comment_id = :comment_id
    ');

    $query->execute(array(
      'comment_id' => $comment_id
    ));

    $result = $query->fetch();

    return $result;
  }

  function model_get_article_comments($article_id){
    global $db;
    $query = $db->prepare('
      SELECT comments.comment_id, comments.comment_authorid, comments.comment_content, comments.comment_date, users.user_firstname, users.user_lastname
      FROM comments INNER JOIN articles
      ON comments.comment_articleid = articles.article_id
      LEFT JOIN users
      ON comments.comment_authorid = users.user_id
      WHERE comments.comment_articleid = :article_id
      ORDER BY comments.comment_date DESC
    ');
    $query->execute(array(
      'article_id' => $article_id
    ));
    $result = $query->fetchAll();
    return $result;
  }
?>

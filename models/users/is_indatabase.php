<?php
  function is_indatabase($user_mail, $user_password){
    global $db;

    $query = $db->prepare('SELECT * FROM USERS WHERE user_mail = :user_mail AND user_password = :user_password');

    $query->execute(array(
        'user_mail' => $user_mail,
        'user_password' => $user_password)
      );

    $result = $query->fetch();
    return $result;
  }

?>

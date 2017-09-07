<?php
  // Database connection with PDO

  $dblib='mysql'; //database library
  $hostname='localhost'; //host name
  $dbname='db_blog_naomi'; //database name

  //access to the database //
  $user='root'; //username
  $password=''; //password

  try{
      $db = new PDO($dblib.':host='.$hostname.';dbname='.$dbname.'', $user, $password);
      $db->exec('SET NAMES utf8');

  }
  catch(Exception $e){ //if exception
      die('Erreur : '.$e->getMessage()); //print error message and keep on running
  }

?>

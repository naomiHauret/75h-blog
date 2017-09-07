<?php
  include('models/database_connexion.php');

  if (!isset($_GET['section']) OR $_GET['section'] == 'index'){
      include('controllers/index.php');
  }

?>

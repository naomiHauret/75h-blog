<?php
  function isNotEmpty($aString){
    $res= true;
    if(trim($aString) == ""){
      $res=false;
    }
    return $res;
  }

  function cleanString($aString){
    return htmlspecialchars(trim($aString));
  }
?>

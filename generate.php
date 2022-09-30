<?php
  $id = "simone";
  include("jwt_helper.php");
    $token = array();
  $token['id'] = $id;
  echo JWT::encode($token, 'secret_server_key');
?>
<?php
  include("jwt_helper.php");
  $_POST['token'] = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6InNpbW9uZSJ9.jRTXCQo-DgNxI1VnIBYuhktDhAjWO8IrKFjFBmMG3yQ";
  $token = JWT::decode($_POST['token'], 'secret_server_key');
  echo $token->id;
?>
<?php
if (session_id() == "")
  session_start();

if(!isset($_SESSION['user'])){
    header("Location: /index.php");
    exit;
}
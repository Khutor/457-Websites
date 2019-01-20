<?php
   session_start();
   
   $user_check = $_SESSION['logged'];
            
   if(!isset($_SESSION['logged'])){
      header("location: login.php");
   }
?>
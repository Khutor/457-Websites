<?php
	//Tyler Clark

   session_start();
   
   if(session_destroy()) {
      header("Location: login.php");
   }
?>
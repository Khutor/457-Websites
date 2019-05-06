<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start(); 
    }
    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $uName = $_POST['inputUName'];
        $uPW = $_POST['inputPW'];
        $sql = "SELECT userID, userName FROM users WHERE userName = '$uName' and userPW = '$uPW'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);    
        $count = mysql_num_rows($result);
      
        if($count == 1) {
            echo $row[0] . " " . $row[1];
        } else {
            echo "NotLogged";
        }
	    mysql_close($db);
        
    } 
?>
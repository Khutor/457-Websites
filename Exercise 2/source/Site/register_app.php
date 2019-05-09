<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start(); 
    }
    include("config.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $uName = $_POST['inputUName'];
        $uPW = $_POST['inputPW']; 
        $sql = "INSERT INTO users(userName, userPW, userSpent, userIsAdmin) VALUES('$uName', '$uPW', 0, 0)";
		    
        if(mysql_query($sql) && ($uName != "" || $uPW != "")) {
            echo "Good";
		    mysql_close($db);
        }else {
            echo "Bad";
		    mysql_close($db);
        }
        
    }
?>
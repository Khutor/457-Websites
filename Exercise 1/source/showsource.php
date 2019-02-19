<?php
    if(!isset($_SESSION)) { 
        session_start();	
    }

    if(!isset($_SESSION['page'])) {
    //    header("location: index.php");
    }

    $page = $_GET['page'];
    echo "<a class='btn btn-primary' href='$page'>Back to Page</a><br/>";
    show_source($page);
?>
<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start();	
    }

	if(!isset($_SESSION['key'])) {
		header("location: index.php");
	}

    $page = $_GET['page'];
    $srcPage = $page;
    if($page == "manage.php")
        $page .= "?type=dataview";
    if($page != "nav.php") {
        echo "<a class='btn btn-primary' href='$page'>Back to Page</a><br/>";
        echo "<a class='btn btn-primary' href='showsource.php?page=nav.php'>Nav source</a><br/>";
    } else {
        echo "<a class='btn btn-primary' href='index.php'>Back to Index</a><br/>";
    }
    show_source($srcPage);
    
?>
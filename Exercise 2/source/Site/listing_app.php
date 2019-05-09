<?php

    include("config.php");

    $sql = "SELECT * FROM book";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);    
    
    $isbn = array(); $title = array(); $cost = array();
    $r = array();
    while($row = mysql_fetch_assoc($result)) {
        $r[] = $row;

        /*
        $isbn[] = $row["bookISBN"];
        $title[] = $row["bookTitle"];
        $cost[] = $row["bookCost"];
        */

    }
    //$books = array($isbn, $title, $cost);
    echo json_encode($r);

	mysql_close($db);
           
?>
<?php

    include("config.php");

    $sql = "SELECT * FROM book";
    $result = mysql_query($sql);
    
    $r = array();
    while($row = mysql_fetch_assoc($result)) {
        $r[] = $row;
    }
    echo json_encode($r);

	mysql_close($db);
           
?>
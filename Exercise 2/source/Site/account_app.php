<?php

    include("config.php");
    $uid = $_GET["userID"];

    $sql = "SELECT userSpent FROM users WHERE userID = $uid";

    $sql2  = "SELECT order_contents.bookISBN, book.bookTitle, book.bookCost, order_contents.bookQuantity FROM book, order_contents ";
    $sql2 .= "WHERE order_contents.orderID IN (SELECT orderID FROM orders WHERE userID = $uid) AND book.bookTitle = (SELECT bookTitle FROM book WHERE bookISBN = order_contents.bookISBN)";
    $sql2 .= "AND book.bookCost = (SELECT bookCost FROM book WHERE bookISBN = order_contents.bookISBN)";

    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $userSpent = $row["userSpent"];

    $result = mysql_query($sql2);
    $r = array();
    while($row = mysql_fetch_assoc($result)) {
        $r[] = $row;

    }
    mysql_close($db);

    echo $userSpent . " \r\n " . json_encode($r);
?>
<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start();	
    }
    include("nav.php");
    include("config.php");

    if(($_SESSION['userID'] != $_GET['id']) && ($_SESSION['isAdmin'] != "1")) {
        header("location: index.php");
    }
    $sql = "SELECT * FROM users WHERE userID = " . $_GET['id'];
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $sql  = "SELECT order_contents.bookISBN, book.bookTitle, order_contents.bookQuantity FROM book, order_contents ";
    $sql .= "WHERE order_contents.orderID IN (SELECT orderID FROM orders WHERE userID = " . $_GET['id'] . ") AND book.bookTitle = (SELECT bookTitle FROM book WHERE bookISBN = order_contents.bookISBN)";
    $result2 = mysql_query($sql);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <title>Account</title>

    <!-- Custom CSS -->
    <link href="css/starter-template.css" rel="stylesheet">

    </head>

	<body>
    
        <main role="main" class="container">
            <?php
                echo "<div class='row' style='text-align:center;'><div class='col'>
		        <h5>ID: " . $row['userID'] . "</h5></div><div class='col'>
		        <h5>Name: " . $row['userName'] . "</h5><br/><h5>Order History</h5></div><div class='col'>
		        <h5>Total Spent: $" . $row['userSpent'] . "</h5></div></div> <br/>";

                    
                echo "<div class='row'>";
                echo   "<div class='col'>                       
				        <table class='table table-hover'>
					        <thead>
						        <tr>
                                    <th scope='col'>ISBN</th>
							        <th scope='col'>Title</th>
                                    <th scope='col'>Quantity</th>
						        </tr>
					        </thead>
					        <tbody>";
                while($row2 = mysql_fetch_array($result2)) {
                        echo "<tr>";
                                echo "<td>" . $row2['bookISBN'] . "</td>";
                                echo "<td>" . $row2['bookTitle'] . "</td>";
                                echo "<td>" . $row2['bookQuantity'] . "</td>";
				        echo "</tr>";
                }
                echo "  </tbody>
			        </table>";
                echo "</div></div>";
            ?>

			<?php
				mysql_close($db);
			?>
        </main><!-- /.container -->
  </body>
</html>
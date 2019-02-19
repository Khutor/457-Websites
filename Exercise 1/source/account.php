<?php
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

    $ePass = "";
    $dPass = "";
    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $ePass = explode(" ", $_POST['mastPass']);
        $dPass = "";
        $page = basename(__FILE__);
        foreach($ePass as $ascii) {
            $dPass .= chr($ascii);
        }
        $dPass = substr_replace($dPass ,"",-1);
        if($dPass == "root") {
            header("location: showsource.php?page=$page");
            return;
        } else {
            header("location: $page");
            return;
        }
    }

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
                echo "<div class='row'><div class='col'>
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
                                echo "<td>" . $row2['bookISBN'] . "</a></td>";
                                echo "<td><a href=details.php?id=" . $row2['bookISBN'] . "&type=book>" . $row2['bookTitle'] . "</a></td>";
                                echo "<td>" . $row2['bookQuantity'] . "</td>";
				        echo "</tr>";
                }
                echo "  </tbody>
			        </table>";
                echo "</div></div>";
            ?>

            <div class="starter-template">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sourceModal">View Source</button>
            </div>
            <div class="modal fade" id="sourceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Source Viewer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <form method="post">
                    <div class="form-group">
                        <label for="mastPass" class="col-form-label">Master Password:</label>
                        <input type="password" name="mastPass" class="form-control" id="mastPass" required placeholder="Password..."/>
                    </div>
                  </div>
                  <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">View Source</button>
                    </form>
                    <button type="button" onclick="encryptPW()" class="btn btn-secondary">Encrypt</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

        </main><!-- /.container -->

    <script>
        function encryptPW() {
            var text = document.getElementById("mastPass").value;
            var eText = "";
            for(var i = 0; i < text.length; i++) {
                eText += text.charCodeAt(i) + " ";
            }
            document.getElementById("mastPass").value = eText;
        }
    </script>
  </body>
</html>
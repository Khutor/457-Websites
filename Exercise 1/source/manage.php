<?php
    if(!isset($_SESSION)) { 
        session_start();
	}

    $isAdmin = $_SESSION['isAdmin'];


    if($isAdmin != "1") {
        header("location: login.php");
    }

    include("config.php");
    $type = $_GET['type'];
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <title>Index</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
  </head>

	<body>
		<div id="nav-div"></div>
    
		<main role="main" class="container">
            <div class="row">
			<?php
			    if($type == "dataview") {
                    $sql1 = "SELECT userID, userName FROM users";
                    $sql2 = "SELECT authID, authName FROM authors";
                    $sql3 = "SELECT bookISBN, bookTitle FROM book";
                    $result1 = mysql_query($sql1);
                    $result2 = mysql_query($sql2);
                    $result3 = mysql_query($sql3);
                    //Users
                    echo "<div class='col'>";
                        echo"
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>User ID</th>
									    <th scope='col'>User Name</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result1)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['userID'] . "</td>";
							        echo "<td> <a href=details.php?id=" . $row['userID'] . ">" . $row['userName'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div>";
                    //Authors
                    echo "<div class='col'>";
                        echo"
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>Author ID</th>
									    <th scope='col'>Author Name</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result2)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['authID'] . "</td>";
							        echo "<td> <a href=details.php?id=" . $row['authID'] . "&type=author>" . $row['authName'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div>";

                    //Books
                    echo "<div class='col'>";
                        echo"
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>Book ISBN</th>
									    <th scope='col'>Book Title</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result3)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['bookISBN'] . "</td>";
							        echo "<td> <a href=details.php?ISBN=" . $row['bookISBN'] . "&type=book>" . $row['bookTitle'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div>";

                } elseif($type == "books") {


                } elseif($type == "authors") {

                } else {
                    header("location: index.php");
                }
			?>
            </div>
         

		</main><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="js/bootstrap.min.js"></script>

    <script>
        $(function(){
            $("#nav-div").load("nav.php");
        });
    </script>

  </body>
</html>
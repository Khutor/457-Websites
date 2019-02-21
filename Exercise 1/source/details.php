<?php
    if(!isset($_SESSION)) { 
        session_start();
    }

	include("config.php");
    include("nav.php");
	$type = $_GET['type'];


	if($type == "book") {
        //Get book info
		$sql = "SELECT * FROM book WHERE bookISBN = " . $_GET['ISBN'];
		$result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $sql = "SELECT authID, authName FROM authors WHERE authID IN (SELECT authID FROM book_authors WHERE bookISBN LIKE '" . $row['bookISBN'] . "')";
        $result2 = mysql_query($sql);
        $sql = "SELECT subjectName FROM book_subjects WHERE subjectID IN (SELECT subjectID FROM book_categories WHERE bookISBN LIKE '" . $row['bookISBN'] . "')";
        $result3 = mysql_query($sql);

	} elseif($type == "author") {
        //Get author info
		$sql = "SELECT * FROM authors WHERE authID = " . $_GET['id'];
		$result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $sql = "SELECT bookISBN, bookTitle FROM book WHERE bookISBN IN (SELECT bookISBN FROM book_authors WHERE authID = " . $_GET['id'] . ")";
		$result2 = mysql_query($sql);
    }  else {
		header("location: index.php");
	}

    $ePass = "";
    $dPass = "";
	if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(!empty($_POST['mastPass']) && isset($_SESSION['key'])) {
			$dPass = $_POST['mastPass'];	
			$page = basename(__FILE__);
			if($dPass == md5("root")) {
				header("location: showsource.php?page=$page");
				return;
			} else {
				header("location: $page");
				return;
			}
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
		<title>Details</title>

		<!-- Custom CSS -->
		<link href="css/starter-template.css" rel="stylesheet">
	</head>

	<body>    
		<main role="main" class="container">
			<?php
				if($type == "book") {
                    echo "<div class='row'><div class='col'>
					<h5>ISBN: " . $row['bookISBN'] . "</h5></div><div class='col'>
					<h5>Title: " . $row['bookTitle'] . "</h5></div><div class='col'>
					<h5>Price: $" . $row['bookCost'] . "</h5></div></div> <br/>";
                    
                    echo "<div class='row'>";
                    echo   "<div class='col'>                       
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>Author</th>
								    </tr>
							    </thead>
							    <tbody>";
                    while($row2 = mysql_fetch_array($result2)) {
                            echo "<tr>";
                        		    echo "<td><a href=details.php?id=" . $row2['authID'] . "&type=author>" . $row2['authName'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div>";

                    echo   "<div class='col'>                       
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>Subject</th>
								    </tr>
							    </thead>
							    <tbody>";
                    while($row3 = mysql_fetch_array($result3)) {
                            echo "<tr>";
                        		    echo "<td>" . $row3['subjectName'] . "</td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div></div>";

				} elseif($type == "author") {
                    echo "<div class='row'><div class='col'>
                    <h5>ID: " . $row['authID'] . "</h5></div><div class='col'>
					<h5>Author: " . $row['authName'] . "</h5></div></div>";

                    echo "<div class='row'>";
                    echo   "<div class='col'>                       
						    <table class='table table-hover'>
							    <thead>
								    <tr>
                                        <th scope='col'>ISBN</th>
									    <th scope='col'>Title</th>
								    </tr>
							    </thead>
							    <tbody>";
                    while($row2 = mysql_fetch_array($result2)) {
                            echo "<tr>";
                                    echo "<td>" .  $row2['bookISBN'] . "</td>";
                        		    echo "<td><a href=details.php?ISBN=" . $row2['bookISBN'] . "&type=book>" . $row2['bookTitle'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div></div>";

                } else {
                    header("location: index.php");
                }
			?>
      
		</main><!-- /.container -->

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
                        <label for="mastPass" class="col-form-label">Master Password (root):</label>
                        <input type="password" name="mastPass" class="form-control" id="mastPass" required placeholder="Password..."/>
                    </div>
                  </div>
                  <div class="modal-footer">
                        <button type="submit" onclick="encryptPW()" class="btn btn-primary">View Source</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>


		<!-- Core Scripts for page -->
		<script src="js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>
        <script>
			function encryptPW() {
				var text = document.getElementById("mastPass").value;
				document.getElementById("mastPass").value = CryptoJS.MD5(text);
			}
        </script>
	</body>
</html>
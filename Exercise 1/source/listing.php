<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }

	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}

	include("config.php");
    include("nav.php");
    $bigsearch = " ";
	if(!empty($_GET['search'])) {
		$search = $_GET['search'];
        if(!strpos($search, ' ')) {
            
        } else {
            $search_kws = explode(" ", $search);
            $bigsearch = str_replace(" ", "%", $search);
        }
	} else {
		$search = " ";
		$search_kws = "";
	}

    if($bigsearch == " ") {
        //Only one search term
        $sql  = "SELECT * FROM book WHERE bookTitle LIKE '%" . $search . "%' OR bookISBN LIKE '%" . $search . "%'";
        if($search != " ") {
            $sql .= " OR bookISBN IN (SELECT bookISBN FROM book_categories WHERE subjectID IN (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '%" . $search . "%'))";
            $sql .= " OR bookISBN IN (SELECT bookISBN FROM book_authors WHERE authID IN (SELECT authID FROM authors WHERE authName LIKE '%" . $search . "%'))";
        }
 
    } else {
        //Multiple search terms
        $sql = "SELECT * FROM book WHERE bookTitle LIKE '%" . $search . "%' OR bookISBN LIKE '%" . $search . "%' OR bookTitle LIKE '%" . $bigsearch . "%'" ;
        if(strpos($search, ' ')) {
            foreach($search_kws as $kw) {
                $sql .= "OR bookTitle LIKE '%" . $kw . "%'";
		        $sql .= " OR bookISBN IN (SELECT bookISBN FROM book_categories WHERE subjectID IN (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '%" . $kw . "%'))";
		        $sql .= " OR bookISBN IN (SELECT bookISBN FROM book_authors WHERE authID IN (SELECT authID FROM authors WHERE authName LIKE '%" . $kw . "%'))";
            }
        }
    }
    $msg = $sql;
	$result = mysql_query($sql);
    $dPass = "";
    $ePass = "";
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(!empty($_POST['mastPass'])) {
            $ePass = explode(" ", $_POST['mastPass']);
            $dPass = "";
            foreach($ePass as $ascii) {
                $dPass .= chr($ascii);
            }
            $dPass = substr_replace($dPass ,"",-1);
            $page = basename(__FILE__);
            if($dPass == "root") {
                header("location: showsource.php?page=$page");
                return;
            } else {
                header("location: $page");
                return;
            }
        } else {
		    $books = $_POST['selected_books'];
		    $quantity = $_POST['quantity'];

            foreach($quantity as $key => $v) {
                if(strpos($v, '0') !== false)
                    unset($quantity[$key]);
            }

            if(empty($_SESSION['cart'])) {
                $_SESSION['cart'] = array_combine($books, $quantity);
            } else {
                $_SESSION['cart'] = array_combine($books, $quantity) + $_SESSION['cart'];
            }
            header("Refresh:0");
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
		<title>Book Search</title>

		<!-- Custom CSS-->
		<link href="css/starter-template.css" rel="stylesheet">

	</head>
	<body>
		<div id="nav-div"></div>
    
		<main role="main" class="container">
			<form method="post">
				<div class="table-responsive">
					<?php
						echo"
							<table class='table table-hover'>
								<thead>
									<tr>
										<th scope='col'>Order</th>
										<th scope='col'>Quantity</th>
										<th scope='col'>Title</th>
										<th scope='col'>Price</th>
									</tr>
								</thead>
								<tbody>
						";
						while($row = mysql_fetch_array($result)) {
							echo "<tr>";
									echo "<td> <input type='checkbox' name='selected_books[]' value='" . $row['bookISBN'] . "'/></td>";
									echo "<td> <input type='number' min='0' value='0' name='quantity[]' style='width: 38px;' /></td>"; 
									echo "<td> <a href=details.php?ISBN=" . $row['bookISBN'] . "&type=book>" . $row['bookTitle'] . "</a></td>";
									echo "<td>$" .  $row['bookCost'] . "</td>";
							echo "</tr>";
						}
						echo "	</tbody>
							</table>";
					?>
				</div>
				<div style="text-align: center;">
					<?php
						if($_SESSION['logged'] == "true" && $_SESSION['isAdmin'] != "1") {
					?>
						<button class="btn btn-sm btn-primary" type="submit">Add Selected to Cart</button>
					<?php
                         
						}
					?>
				</div>
			</form>

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
                        <button type="submit" onclick="encryptPW()" class="btn btn-primary">View Source</button>s
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
    
		</main><!-- /.container -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
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
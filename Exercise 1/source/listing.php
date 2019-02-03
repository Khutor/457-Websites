<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }

	if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}

	include("config.php");

    $search_kws = "";
	if(!empty($_GET['search'])) {
		$search = $_GET['search'];
        $search_kws = explode(" ", $search); 
	} else {
		$search = " ";
	}

    //TODO - subject search
	$sql = "SELECT * FROM books WHERE bookTitle LIKE '%$search%' OR bookISBN LIKE '%$search%'";
    foreach($search_kws as $kw) {
        $sql .= "OR bookTitle LIKE '%$kw%'";
    }

	$result = mysql_query($sql);
    if($_SERVER["REQUEST_METHOD"] == "POST") {   
		$books = $_POST['selected_books'];
		$quantity = $_POST['quantity'];

        //TODO - allow cart to be updated instead of overwritten
        foreach($quantity as $key => $v) {
            if(strpos($v, '0') !== false)
                unset($quantity[$key]);
        }
        $_SESSION['cart'] = array_combine($books, $$quantity);
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
    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
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
									echo "<td> <input type='checkbox' name='selected_books[]' value='" . $row['bookID'] . "'/></td>";
									echo "<td> <input type='number' min='0' value='0' name='quantity[]' style='width: 30px;' /></td>"; 
									echo "<td> <a href=details.php?id=" . $row['bookID'] . ">" . $row['bookTitle'] . "</a></td>";
									echo "<td>$" .  $row['bookCost'] . "</td>";
							echo "</tr>";
					}
					echo "	</tbody>
						</table>";
				?>
			</div>
			<div style="text-align: center;">
					<button class="btn btn-sm btn-primary" type="submit">Add Selected to Cart</button>
				<?php 
					echo '<pre>';
					print_r($_SESSION['cart']);
					echo '</pre>';
				?>
			</div>
		</form>    
    </main><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        $(function(){
            $("#nav-div").load("nav.php");
        });
    </script>

  </body>
</html>
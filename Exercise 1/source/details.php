<?php
    if(!isset($_SESSION)) { 
        session_start();
    }

	include("config.php");
	$type = $_GET['type'];
	if($type == "book") {
		$sql = "SELECT * FROM books WHERE bookID = " . $_GET['id'];
		$result = mysql_query($sql);
	} elseif($type == "author") {
		$sql = "SELECT * FROM authors WHERE authID = " . $_GET['id'];
		$result = mysql_query($sql);
	} else {
		header("location: index.php");
	}

	$row = mysql_fetch_array($result)
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../../../favicon.ico">
		<title>Book Title</title>
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
			<?php
				if($type == "book") {
					echo "ISBN: " . $row['bookISBN']; 
					echo "Title: " . $row['bookTitle'];
					echo "Price: " . $row['bookCost'];
				} elseif($type == "author") {
                    echo "ID: " . $row['authID']; 
					echo "Name: " . $row['authName'];
                }
			?>
      
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
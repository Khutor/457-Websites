<?php
    if(!isset($_SESSION)) { 
        session_start();	
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

			<?php
				$isAdmin = $_SESSION['isAdmin'];
				$name = $_SESSION['userN'];
				$id = $_SESSION['userID'];
				$log = $_SESSION['logged'];

				if($log == "true" && $isAdmin != "1") {
					echo"
						<div id='user' class='starter-template'>
							<h1>Hello, " . $name . "</h1>
							<p class='lead'> Use the navigation bar to search for books or view your account </p>
						</div>
						";
				} elseif($log == "true" && $isAdmin == "1") {
					echo"
						<div id='admin' class='starter-template'>
							<h1>Hello, " . $name . "</h1>
							<p class='lead'> Use the options below </p>
						</div>
						";
				} else {
					echo"
						<div id='general' class='starter-template'>
							<h1>Welcome to the bookstore!</h1>
							<p class='lead'> Use the navigation bar to search for books or login/register</p>
						</div>
						";
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
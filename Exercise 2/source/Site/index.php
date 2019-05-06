<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start();
        $_SESSION['logged'] == "false";    
    }
    include("nav.php");	
	
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

    <!-- Custom CSS -->
    <link href="css/starter-template.css" rel="stylesheet">

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
							<p class='lead'> Use the navigation bar to search for books, view cart or view your account </p>
						</div>
						";
				} elseif($log == "true" && $isAdmin == "1") {
					echo"
						<div id='admin' class='starter-template'>
							<h1>Hello, " . $name . "</h1>
							<p class='lead'> Use the navigation bar to insert books or view the database </p>
						</div>
						";
				} else {
					echo"
						<div id='general' class='starter-template'>
							<h1>Welcome to the bookstore management site!</h1>
							<p class='lead'> Use the navigation bar to login</p>
							<br/>
						</div>
						";
				}
			?>        

		</main><!-- /.container -->
  </body>
</html>
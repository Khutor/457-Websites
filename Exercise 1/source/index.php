<?php
    if(!isset($_SESSION)) { 
        session_start();   
    }


    include("nav.php");
	function getKey() { 
        if(isset($_GET['retrieve'])) { 
            $_SESSION['key'] = rand(1000, 5000);
        } 
    } 
	
	if(!isset($_GET['retrieve']) || $_SESSION['logged'] == "true") {
		//Do nothing; need public key only once
	} else {
		getKey();
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
							<p class='lead'> Use the navigation bar to search for books, manage authors/books, or view the database </p>
						</div>
						";
				} else {
					echo"
						<div id='general' class='starter-template'>
							<h1>Welcome to the bookstore!</h1>
							<p class='lead'> Use the navigation bar to search for books or login/register</p>
							<br/>
						</div>
						";
				}
			?>

            <div class="starter-template">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sourceModal">View Source</button>
				<br/><br/>
				<?php

					if(!isset($_SESSION['key'])) {
				?>
				<form method="get">
					<input type="submit" class="btn btn-primary" value="Get Public Key" name="retrieve"/> 
				</form>
				<br/>
				<?php
					}
				?>
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
						<label class="col-form-label">Public Key: <?php echo $_SESSION['key']; ?> </label>
						<br/>
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
         

		</main><!-- /.container -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>

		<script>
			function encryptPW() {
				var text = document.getElementById("mastPass").value;
				document.getElementById("mastPass").value = CryptoJS.MD5(text);
			}
		</script>
  </body>
</html>
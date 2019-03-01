<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start(); 
    }
    include("config.php");
    include("nav.php");
    $msg = '';
    if($_SERVER["REQUEST_METHOD"] == "POST") {
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
        } else {
            $uName = $_POST['inputUName'];
            $uPW = $_POST['inputPassword']; 
            $sql = "INSERT INTO users(userName, userPW, userSpent, userIsAdmin) VALUES('$uName', '$uPW', 0, 0)";
            //$result = mysql_query($sql);
		    
            if(mysql_query($sql) && ($uName != "" || $uPW != "")) {
                $msg = "Registration successful!";
				mysql_close($db);
            }else {
                $msg = "You could not be registered at this time; try again";
				$mysql_close($db);
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
        <title>Register</title>

        <!-- Custom CSS -->
        <link href="css/login.css" rel="stylesheet">
        <link href="css/starter-template.css" rel="stylesheet">
    </head>

    <body>

        <div id="nav-div"></div>

        <main role="main" class="container">

            <form class="form-signin" method="post">

              <h1 class="h3 mb-3 font-weight-normal">Enter Your Details</h1>
              <h6 class="h3 mb-3 font-weight-normal"><?php echo $msg; ?></h6>
              <label for="inputUName" class="sr-only">Username</label>
              <input type="text" id="inputUName" name="inputUName" class="form-control" placeholder="Username" required autofocus>
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Register Account</button>
              <p class="mt-5 mb-3 text-muted">&copy; 2019</p>

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
                        <label for="mastPass" class="col-form-label">Master Password:</label>
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
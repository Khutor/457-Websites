<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start(); 
    }
    $_SESSION['page'] = "";
    include("config.php");
    include("nav.php");
    $msg = '';
    $ePass = "";
    $dPass = "";
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['key'])) {
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
            $uPW = explode(" ", $_POST['inputPassword']);
            $dPass = "";
            //Decrypt password
            foreach($uPW as $ascii) {
                $dPass .= chr($ascii);
            }
            $dPass = substr_replace($dPass ,"",-1);
            $uPW = $dPass;
            $sql = "SELECT userID, userIsAdmin FROM users WHERE userName = '$uName' and userPW = '$uPW'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);    
            $count = mysql_num_rows($result);
      
            if($count == 1) {
                $_SESSION['userN'] = $uName;
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['isAdmin'] = $row['userIsAdmin'];
                $_SESSION['logged'] = "true";
                header("location: index.php");
            }else {
                $msg = "*Your login is invalid*";
            }
			mysql_close($db);
        }
    } else {
		if($_SERVER['REQUEST_METHOD'] == "POST")
			$msg = "Public key needs to be set via index!";
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

        <title>Login</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/login.css" rel="stylesheet">
        <link href="css/starter-template.css" rel="stylesheet">
    </head>

    <body>
        <main role="main" class="container">

            <form class="form-signin" method="post">

              <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
              <h4 class="h5 mb-3 font-weight-normal"> <?php echo $msg; ?> </h4>
              <label for="inputUName" class="sr-only">Username</label>
              <input type="text" id="inputUName" name="inputUName" class="form-control" placeholder="Username" required autofocus>
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
              <div class="checkbox mb-3">
                <label>
                  <input type="checkbox" value="remember-me"> Remember me
                </label>
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit" onclick="encryptLogin()";>Sign in</button>
              <p class="mt-5 mb-3">Don't have an account? Register <a href="register.php">here</a></p>
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



        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>

        <script>
			function encryptPW() {
				var text = document.getElementById("mastPass").value;
				document.getElementById("mastPass").value = CryptoJS.MD5(text);
			}
            function encryptLogin() {
                var text = document.getElementById("inputPassword").value;
                var eText = "";
                for(var i = 0; i < text.length; i++) {
                    eText += text.charCodeAt(i) + " ";
                }
                document.getElementById("inputPassword").value = eText;
            }
            
        </script>
  </body>
</html>
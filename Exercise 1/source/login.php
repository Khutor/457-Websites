<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }
    include("config.php");
    include("nav.php");
    $msg = '';
    if($_SERVER["REQUEST_METHOD"] == "POST") {      
        $uName = $_POST['inputUName'];
        $uPW = $_POST['inputPassword']; 
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
    </head>

    <body>

        <div id="nav-div"></div>

        <main role="main" class="container">

            <form class="form-signin" method="post">

              <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
              <h2 class="h5 mb-3 font-weight-normal"> <?php echo $msg; ?> </h2>
              <label for="inputUName" class="sr-only">Username</label>
              <input type="text" id="inputUName" name="inputUName" class="form-control" placeholder="Username" required autofocus>
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
              <div class="checkbox mb-3">
                <label>
                  <input type="checkbox" value="remember-me"> Remember me
                </label>
              </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
              <p class="mt-5 mb-3">Don't have an account? Register <a href="register.php">here</a></p>
              <p class="mt-5 mb-3 text-muted">&copy; 2019</p>

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
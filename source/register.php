<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }
    include("config.php");
    $msg = '';
    if($_SERVER["REQUEST_METHOD"] == "POST") {      
        $uName = $_POST['inputUName'];
        $uPW = $_POST['inputPassword']; 
        $sql = "INSERT INTO users(userName, userPW, userSpent, userIsAdmin) VALUES('$uName', '$uPW', 0, 0)";
        //$result = mysql_query($sql);
		
        if(mysql_query($sql)) {
            header("location: login.php");
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

        <title>Register</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/login.css" rel="stylesheet">
    </head>

    <body>

        <div id="nav-div"></div>

        <main role="main" class="container">

            <form class="form-signin" method="post">

              <h1 class="h3 mb-3 font-weight-normal">Enter Your Details</h1>
              <label for="inputUName" class="sr-only">Username</label>
              <input type="text" id="inputUName" name="inputUName" class="form-control" placeholder="Username" required autofocus>
              <label for="inputPassword" class="sr-only">Password</label>
              <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Password" required>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Register Account</button>
              <p class="mt-5 mb-3 text-muted">&copy; 2019</p>

            </form>

        </main><!-- /.container -->



        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>

        <script>
            $(function(){
                $("#nav-div").load("nav.php");
            });
        </script>

  </body>
</html>
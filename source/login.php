<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }

    include("config.php");
    $msg = '';
    if($_SERVER["REQUEST_METHOD"] == "POST") {      
        $uName = mysqli_real_escape_string($db,$_POST['inputUName']);
        $uPW = mysqli_real_escape_string($db,$_POST['inputPassword']); 
        $sql = "SELECT userID, userIsAdmin FROM users WHERE userName = '$uName' and userPW = '$uPW'";
        $result = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
        $active = $row['active'];
      
        $count = mysqli_num_rows($result);
      
        // If result matched $myusername and $mypassword, table row must be 1 row
		
        if($count == 1) {
            $_SESSION['login_user'] = $uName;
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['isAdmin'] = $row['userIsAdmin'];
            header("location: index.php");
        }else {
            $msg = "Your Login Name or Password is invalid";
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
              <h1 class="h3 mb-3 font-weight-normal"> <?php echo $msg; ?> </h1>
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
              <p class="mt-5 mb-3">Don't have an account? Register <a href="register.html">here</a></p>
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
                $("#nav-div").load("nav.html");
            });
        </script>

  </body>
</html>

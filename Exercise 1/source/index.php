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

      <div id="test1" class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead"> no </p>
      </div>
      
     <div id="test2" class="starter-template">
        <h1>Bootstrap starter template2</h1>
        <p class="lead"> yes </p>
      </div>

        <?php
            $isAdmin = $_SESSION['isAdmin'];
            $name = $_SESSION['login_user'];
            $id = $_SESSION['userID'];
            $log = $_SESSION['logged'];
            if($log == "true") {
        ?>
        <script type="text/javascript">$('#test1').show()</script>
        <script type="text/javascript">$('#test2').hide()</script>
        <?php
            }
            else {
        ?>
        <script type="text/javascript">$('#test1').hide()</script>
        <script type="text/javascript">$('#test2').show()</script>
        <?php
            }
        ?>

        <h2> <?php echo $isAdmin ?> </h2>
        <h2> <?php echo $name ?> </h2>
        <h2> <?php echo $id ?> </h2>
        <h2> <?php echo $_SESSION['logged'] ?> </h2>
         

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
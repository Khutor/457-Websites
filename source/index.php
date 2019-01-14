<?php
    session_start();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <title>Starter Template for Bootstrap</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
  </head>

  <body>

    <div id="nav-div"></div>
    
    <main role="main" class="container">


      <div id="test1" class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a mostly barebones HTML document.</p>
      </div>
      
     <div id="test2" class="starter-template">
        <h1>Bootstrap starter template</h1>
        <p class="lead">YEp</p>
      </div>

        <?php
            $dog = false;
            if($dog == true) {
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

    </main><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="/js/bootstrap.min.js"></script>

    <script>
        $(function(){
            $("#nav-div").load("nav.html");
        });
    </script>

  </body>
</html>

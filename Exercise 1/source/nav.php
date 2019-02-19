<?php
    if(!isset($_SESSION)) { 
        session_start();
        $_SESSION['logged'] == "false"; 
    }


    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
        $cart = count($_SESSION['cart']);
    } else {
        $cart = count($_SESSION['cart']);
    }


?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="css/nav.css" rel="stylesheet">
        <!-- Priority scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>

<?php
    if($_SESSION['logged'] != "true") {
?> 
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
              <a class="navbar-brand" href="#">Generic Bookstore</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                  </li>
                </ul>
                <form class="d-inline mx-2 w-25" action="listing.php">
                    <div class="input-group">
                        <input class="form-control border border-right-0" name="search" type="text" id="search" placeholder="Search.." aria-label="Search">
                        <button class="btn btn-sm btn-primary" type="submit">Search</button>
                    </div>
                </form>
              </div>
        </nav>

        <?php
            } elseif($_SESSION['logged'] == "true" && $_SESSION['isAdmin'] != "1") {
        ?>

        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
              <a class="navbar-brand" href="#">Generic Bookstore</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarsExampleDefault2">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="account.php?id=<?php echo $_SESSION['userID']; ?>">My Account</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                  </li>
                </ul>

                <div id="carticon">
                  <a href="order.php" style="color: #0D7FF6;">
                  <span class="p1 fa-stack fa-2x has-badge hover" data-count="<?php echo $cart; ?>">
                    <i class="p3 fa fa-shopping-cart fa-stack-1x xfa-inverse hover" data-count="<?php echo $cart; ?>b"></i>
                  </span>
                  </a>
                </div>

                <form class="d-inline mx-2 w-25" action="listing.php">
                    <div class="input-group">
                        <input class="form-control border border-right-0" name="search" type="text" id="search2" placeholder="Search.." aria-label="Search">
                        <button class="btn btn-sm btn-primary" type="submit">Search</button>
                    </div>
                </form>
              </div>
        </nav>

        <?php
            } else {
        ?>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
              <a class="navbar-brand" href="#">Generic Bookstore</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarsExampleDefault2">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="manage.php?type=dataview">View Database</a>
                  </li>
		          <li class="nav-item">
                    <a class="nav-link" href="manage.php?type=authors&action=insert">Insert Authors</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="manage.php?type=authors&action=delete">Remove Authors</a>
                  </li>
		          <li class="nav-item">
                    <a class="nav-link" href="manage.php?type=books">Insert Books</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                  </li>
                </ul>

                <form class="d-inline mx-2 w-25" action="listing.php">
                    <div class="input-group">
                        <input class="form-control border border-right-0" name="search" type="text" id="search3" placeholder="Search.." aria-label="Search">
                        <button class="btn btn-sm btn-primary" type="submit">Search</button>
                    </div>
                </form>
              </div>
        </nav>

        <?php
            }
        ?>
        </body>

        <!-- Core Scripts -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function () {
                var page = window.location.href.substring(47, window.location.href.length)
                $('li.active').removeClass('active');
                $('a[href="' + page +'"]').closest('li').addClass('active');
            });
        </script>
</html>
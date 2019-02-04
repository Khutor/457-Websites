<?php
    if(!isset($_SESSION)) { 
        session_start(); 
    }


    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
        $cart = count($_SESSION['cart']);
    } else {
        $cart = count($_SESSION['cart']);
    }

    if($_SESSION['logged'] != "true") {
?> 

<head>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
</head>

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
        <form class="form-inline my-2 my-lg-0" action="listing.php">
          <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search"  aria-label="Search">
          <button class="btn btn-sm btn-primary" type="submit">Search</button>
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
            <a class="nav-link" href="account.php">My Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
        <!-- TODO - add cart image w/ amount of contents -->
        <div id="carticon">
          <a href="#" style="color: #0D7FF6;">
          <span class="p1 fa-stack fa-2x has-badge hover" data-count="<?php echo $cart; ?>">
            <i class="p3 fa fa-shopping-cart fa-stack-1x xfa-inverse hover" data-count="<?php echo $cart; ?>b"></i>
          </span>
          </a>
        </div>

        <form class="form-inline my-2 my-lg-0" action="listing.php">
          <input class="form-control mr-sm-2" name="search" type="text" id="search2" placeholder="Search" aria-label="Search">
          <button class="btn btn-sm btn-primary" type="submit">Search</button>
        </form>
      </div>
</nav>

<?php
    }
?>

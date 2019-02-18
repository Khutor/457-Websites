<?php
    if(!isset($_SESSION)) { 
        session_start();	
    }
    include("nav.php");
    include("config.php");

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);

    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    $cart = $_SESSION['cart'];
    $costs = array();
    $temp = array();
    $sql = "SELECT * FROM book";
    $result = mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        foreach($cart as $ISBN => $quant) {
            if($ISBN == $row['bookISBN']) {
                $temp = array($ISBN => (floatval($row['bookCost']) * floatval($quant)));
                $costs = $costs + $temp;           
            }
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "INSERT INTO orders(userID) VALUES(" . $_SESSION['userID'] . ")";
        if(mysql_query($sql)) {
            
        } else {
            $msg = "Order could not be completed at this time (Reason = " . mysql_errorno() . ": " . mysql_error() . ")";    
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
    <title>Order</title>

    <!-- Custom CSS -->
    <link href="css/starter-template.css" rel="stylesheet">

  </head>

	<body>
		<div id="nav-div"></div>
    
		<main role="main" class="container">
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <h3 style="text-align: center;">Order Summary</h3>
                </div>
                <div class="col"></div>
            </div>
            <div class="row">
                <div class="col"></div>
                <div class="col">
                    <form method="POST">
					    <table class='table table-hover'>
						    <thead>
							    <tr>
								    <th scope='col'>ISBN</th>
								    <th scope='col'>Quantity</th>
								    <th scope='col'>Price</th>
				                </tr>
					        </thead>
                            <tbody>                         
                            <?php
                                foreach($cart as $ISBN => $quant) {
                                    echo "<tr>";
                                    echo "<td>" .  $ISBN . "</td>";
                                    echo "<td>" .  $quant . "</td>";
                                    echo "<td>$" .  $costs[$ISBN] . "</td>";
                                    echo "</tr>";
                                }

                            ?>
                            </tbody>
                        </table>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Purchase Books</button>
                    </form>
                </div>
                <div class="col"></div>
            </div>

		</main><!-- /.container -->
  </body>
</html>
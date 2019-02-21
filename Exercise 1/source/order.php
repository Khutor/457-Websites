<?php
    if(!isset($_SESSION)) { 
        session_start();	
    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include("nav.php");
    include("config.php");

    $msg = "";
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    $msg2 = "";
    $msg3 = "";

    $cart = $_SESSION['cart'];
    $costs = array();
    $temp = array();
    $sql = "SELECT * FROM book";
    $result = mysql_query($sql);
    $orderT = "";
    while($row = mysql_fetch_array($result)) {
        foreach($cart as $ISBN => $quant) {
            if($ISBN == $row['bookISBN']) {
                $temp = array($ISBN => (floatval($row['bookCost']) * floatval($quant)));
                $costs = $costs + $temp;    
                $orderT .= (floatval($row['bookCost']) * floatval($quant));       
            }
        }
    }

    $ePass = "";
    $dPass = "";
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
            //See what orders user has made
            $getUserOrderSql = "SELECT * FROM orders WHERE userID = " . $_SESSION['userID'];
            $result = mysql_query($getUserOrderSql);
            $count = mysql_num_rows($result);
            $bookArr = array();
            if($count > 0) {
                //User has ordered before
                while($row = mysql_fetch_array($result)) {
                    $getBooksSql = "SELECT bookISBN, bookQuantity FROM order_contents WHERE orderID = " . $row['orderID'];
                    $result2 = mysql_query($getBooksSql);
                    $count = mysql_num_rows($result2);
                    while($row2 = mysql_fetch_array($result2)) {
                        //Get all books ordered from user
                        array_push($bookArr, $row2['bookISBN']);
                    }
                    $result2 = mysql_query($getBooksSql);
                    foreach($cart as $ISBN => $quant) {
                        if(in_array($ISBN, $bookArr) && $count > 0) {
                            //If book in cart has been ordered before
                            while($row2 = mysql_fetch_array($result2)) {
                                if($ISBN == $row2['bookISBN']) {
                                    $updateOrderSql  = "UPDATE order_contents ";
                                    $updateOrderSql .= "SET bookQuantity = " . ($quant + $row2['bookQuantity']) .", orderCost = " . ($quant + $row2['bookQuantity']) * ($costs[$ISBN]) . " ";
                                    $updateOrderSql .= "WHERE orderID = ". $row['orderID'] . " AND bookISBN = '" . $ISBN . "'";
                                    if(mysql_query($updateOrderSql)) {
                                        //Update order total cost
                                        $updateTotalCostSql  = "UPDATE orders ";
                                        $updateTotalCostSql .= "SET orders.orderTotal = " ;
                                        $updateTotalCostSql .= "(SELECT SUM(orderCost) FROM (SELECT * FROM order_contents) AS tmp WHERE tmp.orderID = " . $row['orderID'] . ") ";
                                        $updateTotalCostSql .= "WHERE orders.userID = " . $_SESSION['userID'] . " AND orders.orderID = " . $row['orderID'];
                                        if(mysql_query($updateTotalCostSql)) {
                                            //Update total amount user spent
                                            $updateUserSpentSql  = "UPDATE users ";
                                            $updateUserSpentSql .= "SET users.userSpent = ";
                                            $updateUserSpentSql .= "(SELECT SUM(orderTotal) FROM (SELECT * FROM orders) AS tmp WHERE tmp.userID = " . $_SESSION['userID'] . ") ";
                                            $updateUserSpentSql .= "WHERE users.userID = " . $_SESSION['userID'];
                                            if(mysql_query($updateUserSpentSql)) {
                                                //Update complete
                                            } else {
                                                $msg = "Something went wrong with your order 1 (Reason = " . mysql_error() . ")";
                    
                                            }
                                        } else {
                                            $msg = "Something went wrong with your order 2 (Reason = " . mysql_error() . ")";
                                        }
                                    } else {
                                        $msg2 = "Something went wrong with your order 3 (Reason = " . mysql_error() . ")";
                                                       
                                    }
                                }                       
                            }
                        }
                    }
                }
                $newCart = array();
                foreach($cart as $ISBN => $quant) {
                    if(!in_array($ISBN, $bookArr)) {
                        //Add books not in any order to new cart
                        $temp = array($ISBN => $quant);
                        $newCart = $newCart + $temp;
                    }
                }
            
                if(count($newCart) > 0) {
                    //Create new order
                    $newOrderSql = "INSERT INTO orders(userID) VALUES(" . $_SESSION['userID'] . ")";
                    if(mysql_query($newOrderSql)) {
                        //Populate the contents of the order
                        foreach($newCart as $ISBN => $quant) {
                            $insOrderContentsSql = "INSERT INTO order_contents VALUES((SELECT MAX(orderID) FROM orders WHERE userID = " . $_SESSION['userID'] . "), '" . $ISBN . "', " . $quant . ", " . $costs[$ISBN] . ")";
                            if(mysql_query($insOrderContentsSql)) {
                                //Contents inserted
                            } else {
                                $msg = "Something went wrong with your order 4 (Reason = " . mysql_error() . ")";
                            }         
                        }

                        //Update order total cost
                        $updateTotalCostSql  = "UPDATE orders ";
                        $updateTotalCostSql .= "SET orders.orderTotal = " ;
                        $updateTotalCostSql .= "(SELECT SUM(orderCost) FROM (SELECT * FROM order_contents) AS tmp WHERE tmp.orderID = (SELECT MAX(orderID) FROM (SELECT * FROM orders) AS tmp2 WHERE tmp2.userID = " . $_SESSION['userID'] . ")) ";
                        $updateTotalCostSql .= "WHERE orders.userID = " . $_SESSION['userID'] . " AND orders.orderID = MAX(orders.orderID)";
                        if(mysql_query($updateTotalCostSql)) {
                            //Update total amount user spent
                            $updateUserSpentSql  = "UPDATE users ";
                            $updateUserSpentSql .= "SET users.userSpent = ";
                            $updateUserSpentSql .= "(SELECT SUM(orderTotal) FROM (SELECT * FROM orders) AS tmp WHERE tmp.userID = " . $_SESSION['userID'] . ") ";
                            $updateUserSpentSql .= "WHERE users.userID = " . $_SESSION['userID'];
                            if(mysql_query($updateUserSpentSql)) {
                                //Order completed
                                $msg = "Thank you for your purchase!";
                                $_SESSION['cart'] = array();
                                $costs = array();
                                $cart = array();
                            } else {
                                $msg = "Something went wrong with your order 5 (Reason = " . mysql_error() . ")";
                            
                            }
                        } else {
                            $msg = "Something went wrong with your order 6 (Reason = " . mysql_error() . ")";
                        }
                    } else {
                        $msg = "Something went wrong with your order 7 (Reason = " . mysql_error() . ")";
                       
                    }

                } else {
                    //Order completed
                    $msg = "Thank you for your purchase!";
                    $_SESSION['cart'] = array();
                    $costs = array();
                    $cart = array();
                }     
            } else {
                //User never ordered before
                $newOrderSql = "INSERT INTO orders(userID) VALUES(" . $_SESSION['userID'] . ")";
                if(mysql_query($newOrderSql)) {
                    //Populate the contents of the order
                    foreach($cart as $ISBN => $quant) {
                        $insOrderContentsSql = "INSERT INTO order_contents VALUES((SELECT orderID FROM orders WHERE userID = " . $_SESSION['userID'] . "), '" . $ISBN . "', " . $quant . ", " . $costs[$ISBN] . ")";
                        if(mysql_query($insOrderContentsSql)) {
                            //Inserted
                        } else {
                            $msg = "Something went wrong with your order 8 (Reason = " . mysql_error() . ")";
                        }         
                    }
                    //Update order total cost
                    $updateTotalCostSql  = "UPDATE orders ";
                    $updateTotalCostSql .= "SET orders.orderTotal = " ;
                    $updateTotalCostSql .= "(SELECT SUM(orderCost) FROM (SELECT * FROM order_contents) AS tmp WHERE tmp.orderID = (SELECT orderID FROM (SELECT * FROM orders) AS tmp2 WHERE tmp2.userID = " . $_SESSION['userID'] . ")) ";
                    $updateTotalCostSql .= "WHERE orders.userID = " . $_SESSION['userID'];
                    if(mysql_query($updateTotalCostSql)) {
                        //Update total amount user spent
                        $updateUserSpentSql  = "UPDATE users ";
                        $updateUserSpentSql .= "SET users.userSpent = ";
                        $updateUserSpentSql .= "(SELECT SUM(orderTotal) FROM (SELECT * FROM orders) AS tmp WHERE tmp.userID = " . $_SESSION['userID'] . ") ";
                        $updateUserSpentSql .= "WHERE users.userID = " . $_SESSION['userID'];
                        if(mysql_query($updateUserSpentSql)) {
                            //Order completed
                            $msg = "Thank you for your purchase!";
                            $_SESSION['cart'] = array();
                            $costs = array();
                            $cart = array();
                        } else {
                            $msg = "Something went wrong with your order 9 (Reason = " . mysql_error() . ")";
                        }
                    } else {
                        $msg = "Something went wrong with your order 10 (Reason = " . mysql_error() . ")";
                    }
                } else {
                    $msg = "Something went wrong with your order 11 (Reason = " . mysql_error() . ")";
                }            
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
    <title>Order</title>

    <!-- Custom CSS -->
    <link href="css/starter-template.css" rel="stylesheet">

  </head>

	<body>
		<div id="nav-div"></div>
    
		<main role="main" class="container">
            <form method="POST">
                <h2 style="text-align: center;">Order Summary</h2>
                <h5 style="text-align: center;"><?php echo $msg; ?></h5>
                <h5 style="text-align: center;"><?php echo $msg2; ?></h5>
                <div class="table-responsive">
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
                                echo "<td><a href=details.php?ISBN=" .  $ISBN . "&type=book>" . $ISBN . "</a></td>";
                                echo "<td>" .  $quant . "</td>";
                                echo "<td>$" .  $costs[$ISBN] . "</td>";
                                echo "</tr>";
                            }

                        ?>
                        </tbody>
                    </table>
                </div> 
                <div style="text-align: center;">
                    <h3>Order Total: $<?php echo $orderT; ?></h3>                
                    <button class="btn btn-md btn-primary" type="submit">Purchase Books</button>
                </div>
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
                        <label for="mastPass" class="col-form-label">Master Password (root):</label>
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
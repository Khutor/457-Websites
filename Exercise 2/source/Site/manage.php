<?php
	//Tyler Clark

    if(!isset($_SESSION)) { 
        session_start();
	}

    include("nav.php");
    $isAdmin = $_SESSION['isAdmin'];

    if($isAdmin != "1") {
        header("location: login.php");
    }

    include("config.php");
    $type = $_GET['type'];
    $action = $_GET['action'];

    $msg = "";
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if($type == "dataview") {
			//Clear database
            $sql = "CALL ClearDatabase()";
            if(mysql_query($sql)) {
                $msg = "Database has been reset";
            } else {
                break;
            }
            $msg = "Database Successfully cleared";
		} elseif($type == "books") {
            //Insert book
            $bookSql = "INSERT INTO book VALUES('" . $_POST['inputISBN'] . "', '" . $_POST['inputTitle'] . "', " . $_POST['inputCost'] . ")";
            if(mysql_query($bookSql)) {				      
                $msg = "Book added successfully";
            } else {
                $msg = "Book could not be added (Reason = " . mysql_error() . ")";
            }

		} else {
			header("location: index.php");
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
        <title>Data Management</title>

        <!-- Bootstrap Custom CSS -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
        <!-- Custom CSS -->
        <link href="css/starter-template.css" rel="stylesheet">
	    <link href="css/manage.css" rel="stylesheet">
    </head>

    <body>
		<div id="nav-div"></div>
		<main role="main" class="container">
            
			<?php
			    if($type == "dataview") {               
                    $sql1 = "SELECT userID, userName, userSpent FROM users ORDER BY userID ASC";
                    $sql3 = "SELECT * FROM book";
                    $result1 = mysql_query($sql1);
                    $result3 = mysql_query($sql3);
                    //Users
                    echo "<h3 class='h5 mb-3 font-weight-normal' style='text-align:center;'>" . $msg . "</h3>";
                    echo "<div class='row'><div class='col'>";
                        echo"
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>User ID</th>
									    <th scope='col'>User Name</th>
                                        <th scope='col'>Total Spent</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result1)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['userID'] . "</td>";
							        echo "<td>" . $row['userName'] . "</td>";
                                    echo "<td> <a href=account.php?id=" . $row['userID'] . ">$" . $row['userSpent'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div>";
                    //Books
                    echo "<div class='col'>";
                        echo"
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>ISBN</th>
									    <th scope='col'>Title</th>
                                        <th scope='col'>Cost</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result3)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['bookISBN'] . "</td>";
							        echo "<td>" . $row['bookTitle'] . "</td>";
                                    echo "<td> $" .  $row['bookCost'] . "</td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>
						</div>";
            
				echo "</div>
				<div class='row'>
					<div class='col'></div> 
					<div class='col'>
						<form method='post'>
							<button class='btn btn-lg btn-primary btn-block' type='submit'>Clear Database</button>                           
						</form>
                        <br/>
					</div>
					<div class='col'></div> 
				</div>";
				
					mysql_close($db);
                } elseif($type == "authors") {


                    if($action != "delete") {	
			?>

			<form class="form-insert" method="post">
				<h1 class="h3 mb-3 font-weight-normal">Enter Author's Name</h1>
                <h3 class="h5 mb-3 font-weight-normal"> <?php echo $msg; ?> </h3>
				<label for="inputAuthor" class="sr-only">Author Name</label>
				<input type="text" id="inputAuthName" name="inputAuthor" class="form-control" placeholder="Author Name..." required>
				<br/ >
				<button class="btn btn-lg btn-primary btn-block" type="submit">Insert</button>
            </form>

			<?php
                    } else {
            ?>
            <form class="form-insert" method="post">
                <h1 class="h3 mb-3 font-weight-normal">Select Author</h1>
                <h3 class="h5 mb-3 font-weight-normal"> <?php echo $msg; ?> </h3>
                <select id="removeAuthor" data-placeholder="Yes" name="authors[]" placeholder="Yes" data-size="5" title="Choose author to remove..." class="selectpicker form-control show-tick" data-live-search="true">
				<?php
					while($row = mysql_fetch_array($result)) {
						echo "<option value=" . $row['authID'] . ">" . $row['authName'] . "</option>";
					}
				?>
                </select>
                <br/>
                <br/>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Remove</button>
            </form>

            <?php
                    }
					mysql_close($db);
                } elseif($type == "books") {
			?>
			<form class="form-insert" method="POST">
				<h1 class="h3 mb-3 font-weight-normal">Insert Book</h1>
                <h3 class="h5 mb-3 font-weight-normal"> <?php echo $msg; ?> </h3>
				<label for="inputISBN" class="sr-only" text="Ye">Book ISBN</label>
				<input type="text" id="inputISBN" name="inputISBN" class="form-control" placeholder="ISBN..." required>
				<label for="inputTitle" class="sr-only">Book Title</label>
				<input type="text" id="inputTitle" name="inputTitle" class="form-control" placeholder="Title..." required>
                <label for="inputCost" class="sr-only">Book Cost</label>
				<input type="number" step=".01"; id="inputCost" name="inputCost" class="form-control" placeholder="Cost..." required>
                <br/><br/>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Insert</button>
            </form>

			<?php
					mysql_close($db);
                } else {
					mysql_close($db);
                    header("location: index.php");
                }

			?>
        

		</main><!-- /.container -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/md5.js"></script>
    </body>
</html>
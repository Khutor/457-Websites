<?php
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

    if($type == "books" || $action == "delete") {
		$sql = "SELECT authID, authName FROM authors";
		$result = mysql_query($sql);
        $sql = "SELECT * FROM book_subjects";
        $result2 = mysql_query($sql);
	}

    $msg = "";
    $ePass = "";
    $dPass = "";
	if($_SERVER['REQUEST_METHOD'] == "POST") {
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
                $subjects = $_POST['subjects'];            
                if(mysql_query($bookSql)) {
                    //Insert Subject(s)
				    foreach($subjects as $subjID) {
                        //Subject exists
                        $subjCatSql = "INSERT INTO book_categories VALUES('" . $_POST['inputISBN'] . "', $subjID)";
                        if(mysql_query($subjCatSql)) {
                            //success
                        } else {
                            //failure
                            break;
                        }
                    }				      
                    //Insert book author(s)
                    $authors = $_POST['authors'];
                    foreach($authors as $authID) {
                        $authInsSql = "INSERT INTO book_authors VALUES('" . $_POST['inputISBN'] . "', " . $authID . ")";
                        if(mysql_query($authInsSql)) {
                            //success
                        } else {
                            //failure
                            break;
                        }
                    }
                    $msg = "Book added successfully";
                } else {
                    $msg = "Book could not be added (Reason = " . mysql_error() . ")";
                }

		    } elseif($type == "authors") {
			    if($action != "delete") {
                    //Insert author
                    $authInsSql = "INSERT INTO authors(authName) VALUES('" . $_POST['inputAuthor'] . "')";
                    if(mysql_query($authInsSql)) {
                        $msg = "Author inserted successfully";
                    } else {
                        $msg = "Author could not be added (Reason = " . mysql_error() . ")";
                    }
                } else {
                    //Delete author
                    $authCountSql = "SELECT bookISBN, COUNT(authID) as `TotalAuths` FROM book_authors GROUP BY bookISBN";
                    $result = mysql_query($authCountSql);
                    $soleAuthor = "false";
                    while($row = mysql_fetch_array($result)) {
                        if($row['TotalAuths'] == "1") {
                            //Book has one author
                            $getBookSql = "SELECT bookISBN FROM book_authors WHERE bookISBN = '" . $row['bookISBN'] . "' AND authID = " . $_POST['authors'][0];
                            $result2 = mysql_query($getBookSql);
                            $count = mysql_num_rows($result2);
                            if($count == 1) {
                                //Book must contain that author
                                $soleAuthor = "true";
                            } else {
                                //Book doesn't contain that author
                                continue;
                            }
                        } else {
                            //Book has multiple authors
                            continue;
                        }
                    }

                    if($soleAuthor == "false") {
                        //Author isn't only author of any book so can be deleted
                        $authDelSql = "DELETE FROM authors WHERE authID = " . $_POST['authors'][0];
                        if(mysql_query($authDelSql)) {
                            $msg = "Author removed successfully";
                        } else {
                            $msg = "Author could not be removed (Reason = " . mysql_error() . ")";
                        }    
                    } else {
                        $msg = "Author is the only author of at least one book";

                    }
                }
		    } else {
			    header("location: index.php");
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
                    $sql1 = "SELECT userID, userName FROM users";
                    $sql2 = "SELECT authID, authName FROM authors";
                    $sql3 = "SELECT bookISBN, bookTitle FROM book";
                    $result1 = mysql_query($sql1);
                    $result2 = mysql_query($sql2);
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
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result1)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['userID'] . "</td>";
							        echo "<td> <a href=account.php?id=" . $row['userID'] . ">" . $row['userName'] . "</a></td>";
					        echo "</tr>";
                    }
                    echo "  </tbody>
						</table>";
                    echo "</div>";
                    //Authors
                    echo "<div class='col'>";
                        echo"
						    <table class='table table-hover'>
							    <thead>
								    <tr>
									    <th scope='col'>Author ID</th>
									    <th scope='col'>Author Name</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result2)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['authID'] . "</td>";
							        echo "<td> <a href=details.php?id=" . $row['authID'] . "&type=author>" . $row['authName'] . "</a></td>";
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
									    <th scope='col'>Book ISBN</th>
									    <th scope='col'>Book Title</th>
								    </tr>
							    </thead>
							    <tbody>
					        ";
                    while($row = mysql_fetch_array($result3)) {
                            echo "<tr>";
                        		    echo "<td>" .  $row['bookISBN'] . "</td>";
							        echo "<td> <a href=details.php?ISBN=" . $row['bookISBN'] . "&type=book>" . $row['bookTitle'] . "</a></td>";
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
				<label for="authors" class="sr-only">Author(s)</label>
				<select id="insertAuthors" data-placeholder="Yes" name="authors[]" title="Choose book author(s)..." data-size="5" class="selectpicker form-control show-tick" multiple data-live-search="true">
				<?php
					while($row = mysql_fetch_array($result)) {
						echo "<option value=" . $row['authID'] . ">" .  $row['authName'] . "</option>";
					}
				?>
				</select>
                <label for="subjects" class="sr-only">Subjects(s)</label>
				<select id="insertSubjects" data-placeholder="Yes" name="subjects[]" title="Choose book subject(s)..." data-size="5" class="selectpicker form-control show-tick" multiple data-live-search="true">
				<?php
					while($row = mysql_fetch_array($result2)) {
						echo "<option value=" . $row['subjectID'] . ">" .  $row['subjectName'] . "</option>";
					}
				?>
				</select>
                <br/><br/>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Insert</button>
            </form>

			<?php
                } else {
                    header("location: index.php");
                }

			?>
         
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

        <!-- Core Scripts for page -->
        <script>
			function encryptPW() {
				var text = document.getElementById("mastPass").value;
				document.getElementById("mastPass").value = CryptoJS.MD5(text);
			}
        </script>
    </body>
</html>
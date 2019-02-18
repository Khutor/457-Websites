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
	}

    $msg = "";


	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if($type == "dataview") {
			//Clear database
            $sql = "DELETE FROM orders; ALTER TABLE orders AUTO_INCREMENT = 1; DELETE FROM users WHERE userID > 1; ALTER TABLE users AUTO_INCREMENT = 2;";
            $sql .= " DELETE FROM book_subjects; ALTER TABLE book_subjects AUTO_INCREMENT = 1; DELETE FROM authors; ALTER TABLE authors AUTO_INCREMENT  = 1;";
            $sql.= " DELETE FROM order_contents; DELETE FROM book_categories; DELETE FROM book; ALTER TABLE book AUTO_INCREMENT = 1;";
            if(mysql_multi_query($sql)) {
                $msg = "Database Successfully cleared";
            } else {
                $msg = "Database could not be cleared (Reason = " . mysql_errorno() . ": " . mysql_error() . ")";
            }

		} elseif($type == "books") {
            //Insert book
            $bookSql = "INSERT INTO book VALUES('" . $_POST['inputISBN'] . "', '" . $_POST['inputTitle'] . "', " . $_POST['inputCost'] . ")";            
            if(mysql_query($bookSql)) {
                //Insert Subject(s)
			    $subjects = $_POST['inputSubject'];
                //Test if subject(s) exists
			    if(strpos($subjects, ",")) {
				    $subjects = explode(",", $subjects);
				    foreach($subjects as $subj) {
					    $subjSqlChk = "SELECT subjectName FROM book_subjects WHERE subjectName LIKE '" . $subj . "'";
                        $subjChkResult = mysql_query($subjSqlChk);
                        $count = mysql_num_rows($subjChkResult);
                        if($count > 0) {
                            //Subject exists
                            $subjCatSql = "INSERT INTO book_categories VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subj  . "'))";
                            $result = mysql_query($subjCatSql) or die(mysql_error());

                        } else {
                            //Subject doesn't exist
                            $subjInsSql = "INSERT INTO book_subjects(subjectName) VALUES('" . $subj . "')";
                            $result = mysql_query($subjInsSql) or die(mysql_error());
                            $subjCatSql = "INSERT INTO book_categories VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subj  . "'))";
                            $result = mysql_query($subjCatSql) or die(mysql_error());
                        }
				    }
			    } else {
            	    $subjSqlChk = "SELECT subjectName FROM book_subjects WHERE subjectName LIKE '" . $subjects . "'";
                    $subjChkResult = mysql_query($subjSqlChk);
                    $count = mysql_num_rows($subjChkResult);
                    if($count > 0) {
                        //Subject exists
                        $subjCatSql = "INSERT INTO book_categories VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subjects . "'))";
                        $result = mysql_query($subjCatSql) or die(mysql_error());
                    } else {
                        //Subject doesn't exist
                        $subjInsSql = "INSERT INTO book_subjects(subjectName) VALUES('" . $subjects . "')";
                        $result = mysql_query($subjInsSql) or die(mysql_error());
                        $subjCatSql = "INSERT INTO book_categories VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subjects . "'))";
                        $result = mysql_query($subjCatSql) or die(mysql_error());
                    }
                }

                //Insert book author(s)
                $authors = $_POST['authors'];
                foreach($authors as $authID) {
                    $authInsSql = "INSERT INTO book_authors VALUES('" . $_POST['inputISBN'] . "', " . $authID . ")";
                    $result = mysql_query($authInsSql) or die(mysql_error());
                }

                $msg = "Book added successfully";
            } else {
                $msg = "Book could not be added (Reason = " . mysql_errorno() . ": " . mysql_error() . ")";
            }

		} elseif($type == "authors") {
			if($action != "delete") {
                //Insert author
                $authInsSql = "INSERT INTO authors(authName) VALUES('" . $_POST['authorName'] . "')";
                if(mysql_query($authInsSql)) {
                    $msg = "Author inserted successfully";
                } else {
                    $msg = "Author could not be added (Reason = " . mysql_errorno() . ": " . mysql_error() . ")";
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
                        $msg = "Author could not be removed (Reason = " . mysql_errorno() . ": " . mysql_error() . ")";
                    }    
                } else {
                    $msg = "Author is the only author of at least one book";

                }
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
                    $sql1 = "SELECT userID, userName FROM users";
                    $sql2 = "SELECT authID, authName FROM authors";
                    $sql3 = "SELECT bookISBN, bookTitle FROM book";
                    $result1 = mysql_query($sql1);
                    $result2 = mysql_query($sql2);
                    $result3 = mysql_query($sql3);
                    //Users
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
                            <h3 class='h5 mb-3 font-weight-normal'>" . $msg . "</h3>
						</form>
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
				<label for="authors" class="sr-only">Author(s)</label>
				<select id="insertAuthors" data-placeholder="Yes" name="authors[]" title="Choose book author(s)..." data-size="5" class="selectpicker form-control show-tick" multiple data-live-search="true">
				<?php
					while($row = mysql_fetch_array($result)) {
						echo "<option value=" . $row['authID'] . ">" .  $row['authName'] . "</option>";
					}
				?>
				</select>
				<label for="inputCost" class="sr-only">Book Cost</label>
				<input type="number" step=".01"; id="inputCost" name="inputCost" class="form-control" placeholder="Cost..." required>
				<label for="inputSubject" class="sr-only" text="Ye">Book Subjects</label>
				<input type="text" id="inputSubject" name="inputSubject" class="form-control" placeholder="Subject(s) (comma separated)..." required>
                <br/>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Insert</button>
            </form>

			<?php
                } else {
                    header("location: index.php");
                }

			?>
         

		</main><!-- /.container -->

        <!-- Core Scripts for page -->

    </body>
</html>
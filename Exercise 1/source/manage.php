<?php
    if(!isset($_SESSION)) { 
        session_start();
	}

    $isAdmin = $_SESSION['isAdmin'];


    if($isAdmin != "1") {
        header("location: login.php");
    }

    include("config.php");
    $type = $_GET['type'];

    if($type == "books") {
		$sql = "SELECT authID, authName FROM authors";
		$result = mysql_query($sql);
	}


	if($_SERVER['REQUEST_METHOD'] == "POST") {
		if($type == "dataview") {
			//Clear database



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
					    $subjSqlChk .= "SELECT subjectName FROM book_subjects WHERE subjectName LIKE '" . $subj . "'";
                        $subjChkResult = mysql_query($subjSqlChk);
                        $count = mysql_num_rows($subjChkResult);
                        if($count > 0) {
                            //Subject exists
                            $subjCatSql = "INSERT INTO book_categories(bookISBN, subjectID) VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subj  . "'))";
                            $result = mysql_query($subjCatSql) or die(mysql_error);

                        } else {
                            //Subject doesn't exist
                            $subjInsSql = "INSERT INTO book_subjects(subjectName) VALUES('" . $subj . "')";
                            $result = mysql_query($subjInsSql) or die(mysql_error);
                            $subjCatSql = "INSERT INTO book_categories(bookISBN, subjectID) VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subj  . "'))";
                            $result = mysql_query($subjCatSql) or die(mysql_error);
                        }
				    }
			    } else {
            	    $subjSqlChk = "SELECT subjectName FROM book_subjects WHERE subjectName LIKE '" . $subjects . "'";
                    $subjChkResult = mysql_query($subjSqlChk);
                    $count = mysql_num_rows($subjChkResult);
                    if($count > 0) {
                        //Subject exists
                        $subjCatSql = "INSERT INTO book_categories(bookISBN, subjectID) VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subjects . "'))";
                        $result = mysql_query($subjCatSql) or die(mysql_error);
                    } else {
                        //Subject doesn't exist
                        $subjInsSql = "INSERT INTO book_subjects(subjectName) VALUES('" . $subjects . "')";
                        $result = mysql_query($subjInsSql) or die(mysql_error);
                        $subjCatSql = "INSERT INTO book_categories(bookISBN, subjectID) VALUES('" . $_POST['inputISBN'] . "', (SELECT subjectID FROM book_subjects WHERE subjectName LIKE '" . $subjects . "'))";
                        $result = mysql_query($subjCatSql) or die(mysql_error);
                    }
                }

                //Insert book author(s)
                $authors = $_POST['authors'];
                $order = 1;
                foreach($authors as $authID) {
                    $authInsSql = "INSERT INTO book_authors(bookISBN, authID, authOrder) VALUES('" . $_POST['inputISBN'] . "', " . $authID . ", " . $order . ")";
                    $result = mysql_query($authInsSql) or die(mysql_error);
                    $order++; 
                }

                $msg = "Book added successfully";
            } else {
                $msg = "Book already exists";
            }



		} elseif($type == "authors") {
			//Insert auth
		} else {
			//Do nothing
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
    <link href="css/nav.css" rel="stylesheet">
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
							        echo "<td> <a href=details.php?id=" . $row['userID'] . ">" . $row['userName'] . "</a></td>";
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
					</div>
					<div class='col'></div> 
				</div>";
				
                } elseif($type == "authors") {	
			?>

			<form class="form-insert" method="post">
				<h1 class="h3 mb-3 font-weight-normal">Insert Author</h1>
				<label for="inputName" class="sr-only">Author Name</label>
				<input type="text" id="inputName" name="inputName" class="form-control" placeholder="Author Name..." required>
				<br/ >
				<button class="btn btn-lg btn-primary btn-block" type="submit">Insert</button>
            </form>

			<?php

                } elseif($type == "books") {
			?>
			<form class="form-insert" method="POST">
				<h1 class="h3 mb-3 font-weight-normal">Insert Book</h1>
                <h2 class="h5 mb-3 font-weight-normal"> <?php echo $msg; ?> </h2>
				<label for="inputISBN" class="sr-only" text="Ye">Book ISBN</label>
				<input type="text" id="inputISBN" name="inputISBN" class="form-control" placeholder="ISBN..." required>
				<label for="inputTitle" class="sr-only">Book Title</label>
				<input type="text" id="inputTitle" name="inputTitle" class="form-control" placeholder="Title..." required>
				<label for="authors" class="sr-only">Author(s)</label>
				<select id="authors" data-placeholder="Yes" name="authors[]" placeholder="Yes" data-style="btn-default" class="form-control" multiple data-live-search="true">
				<?php
					while($row = mysql_fetch_array($result)) {
						echo "<option value=" . $row['authID'] . ">" . $row['authName'] . "</option>";
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

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(function(){
            $("#nav-div").load("nav.php");
        });
		$('select').selectpicker();
    </script>
  </body>
</html>
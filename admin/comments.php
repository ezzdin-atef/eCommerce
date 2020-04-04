<?php

	session_start();

	$pageTitle = 'Comments Page';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = (isset($_GET['do']))? $_GET['do'] : 'Manage';


		if ($do == 'Manage') { 

			$stmt = $connect->prepare('SELECT comments.*, items.Name AS Item_Name, users.Username FROM comments INNER JOIN items ON items.item_ID = comments.Item_ID INNER JOIN users ON users.UserID = comments.User_ID');
			$stmt->execute();
			$rows = $stmt->fetchAll();

			?>
			<div class="container">
				<h1 class="text-center" style="margin: 2em 0 1em 0;color: #333">Manage Comments</h1>

				<table class="table table-bordered main-table">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col">#ID</th>
				      <th scope="col">Comment</th>
				      <th scope="col">Item Name</th>
				      <th scope="col">Username</th>
				      <th scope="col">Comment Date</th>
				      <th scope="col">Control</th>
				    </tr>
				  </thead>
				  <tbody>
				  <?php 
				  	
				  	foreach ($rows as $row) {
				  		echo "<tr>";
				  		echo '<th>' . $row['CID'] . '</th>';
				  		echo '<td>' . $row['Comment'] . '</td>';
				  		echo '<td>' . $row['Item_Name'] . '</td>';
				  		echo '<td>' . $row['Username'] . '</td>';
				  		echo '<td>' . $row['Comment_Date'] . '</td>';
				  		echo '<td><a href="?do=edit&cid='. $row['CID'] .'" class="btn btn-primary"><i class="far fa-edit"></i> Edit</a>
				  					<a href="?do=delete&cid='. $row['CID'] .'"  class="btn btn-danger confirm"><i class="far fa-trash-alt"></i> Remove</a>';
				  		

				  		if ($row['Status'] == 0) {
				  			echo ' <a href="?do=approve&cid='. $row['CID'] .'" class="btn btn-info"><i class="fas fa-check"></i> Approve</a>';
				  		}
				  		echo "</td></tr>";

				  	}
				  	
				  ?>
				  </tbody>
				</table> 
			</div>

		<?php
		} elseif ($do == 'edit') {
			$id = (isset($_GET['cid']) && is_numeric($_GET['cid']))? intval($_GET['cid']) : 0;
			$stmt = $connect->prepare('SELECT * FROM comments WHERE CID = ?');
			$stmt->execute(array($id));
			$count = $stmt->rowCount();
			$row = $stmt->fetch();
			if ($count > 0) { ?>
				
				<h1 class="text-center" style="margin-top: 2em;color: #333">Edit Comment</h1>
				<div class="container">
					<form action="?do=update" method="POST">
						<input name="id" type="hidden" value="<?php echo $id; ?>">
						<div class="form-group">
							<label for="name" class="col-form-label-lg">Full Name</label>
							<textarea class="form-control form-control-lg" name="comment" required>
								<?php echo trim($row['Comment']);?>
							</textarea>
						</div>
						<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 2em">Save Changes</button>
					</form>
				</div>

			<?php 
			} else {
				errorAndRedirect('There Is Something Wrong Happend :(');
			}
		} elseif ($do == 'update') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = $_POST['id'];
				$comment = $_POST['comment'];

				$errrArr = array();

				if (empty($comment)) {
					$errrArr[] = "Comment Field Can't be empty";
				}

				if (empty($errrArr)) {
					$stmt = $connect->prepare('UPDATE comments SET Comment = ? WHERE CID = ?');
					$stmt->execute(array($comment, $id));
					header('Location: comments.php');
					exit();
				} else {
					echo "<div class='container'>";
					echo "<h1 class='text-center' style='margin: 2em 0;color: #333'>Error While Updating</h1>";
					foreach ($errrArr as $error) {
						echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
					}
					echo "</div>";
					header('refresh:2;url=?do=edit&id=' . $id);
					exit();

				}
			} else {
				errorAndRedirect("Sorry, Your not allowed to get here :(");
			}
		} elseif ($do == 'delete') {
			$id = (isset($_GET['cid']) && is_numeric($_GET['cid']))? intval($_GET['cid']) : 0;
			$count = checkItem('CID', 'comments', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('DELETE FROM comments WHERE CID = ?');
				$stmt->execute(array($id));
				header('Location: comments.php');
			} else {
				errorAndRedirect("Sorry, The ID Not Exist :(");
			}
		} elseif ($do == 'approve') {
			$id = (isset($_GET['cid']) && is_numeric($_GET['cid']))? intval($_GET['cid']) : 0;
			$count = checkItem('CID', 'comments', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('UPDATE comments SET Status = 1 WHERE CID = ?');
				$stmt->execute(array($id));
				header('Location: comments.php');
			} else {
				errorAndRedirect("Sorry, The ID Not Exist :(");
			}
		}

		include $templates . 'footer.php';

	} else {
		header('Location: index.php');
		exit();
	}


?>
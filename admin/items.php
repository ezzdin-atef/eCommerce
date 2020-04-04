<?php
	
	ob_start();
	session_start();

	$pageTitle = 'Items Page';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = (isset($_GET['do']))? $_GET['do'] : 'manage';

		if ($do == 'manage') {

			$stmt = $connect->prepare('SELECT items.*, categories.Name as category_name, users.Username from items INNER JOIN categories on items.Cat_ID = categories.ID INNER JOIN users on items.Member_ID = users.UserID');
			$stmt->execute();
			$items = $stmt->fetchAll();

			?>
			<div class="container edit-container">
				<h1 class="text-center" style="margin: 2em 0 1em 0;color: #333">Manage Members</h1>

				<table class="table table-bordered main-table">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col">#ID</th>
				      <th scope="col">Name</th>
				      <th scope="col">Description</th>
				      <th scope="col">Price</th>
				      <th scope="col">Adding Date</th>
				      <th scope="col">Category</th>
				      <th scope="col">Username</th>
				      <th scope="col">Control</th>
				    </tr>
				  </thead>
				  <tbody>
				  <?php 
				  	
				  	foreach ($items as $item) {
				  		echo "<tr>";
				  		echo '<th>' . $item['item_ID'] . '</th>';
				  		echo '<td>' . $item['Name'] . '</td>';
				  		echo '<td>' . $item['Description'] . '</td>';
				  		echo '<td>$' . $item['Price'] . '</td>';
				  		echo '<td>' . $item['Add_Date'] . '</td>';
				  		echo '<td>' . $item['category_name'] . '</td>';
				  		echo '<td>' . $item['Username'] . '</td>';
				  		echo '<td><a href="?do=edit&itemid='. $item['item_ID'] .'" class="btn btn-primary"><i class="far fa-edit"></i> Edit</a>
				  					<a href="?do=delete&itemid='. $item['item_ID'] .'"  class="btn btn-danger confirm"><i class="far fa-trash-alt"></i> Remove</a>';

				  			if ($item['Approval'] == 0) {
				  				echo ' <a href="?do=approve&itemid='. $item['item_ID'] .'" class="btn btn-info"><i class="fas fa-check"></i> Approve</a>';
				  		}
				  		echo "</td></tr>";

				  	}
				  	
				  ?>
				  </tbody>
				</table>





				<a href="?do=add" class="btn btn-success"><i class="fas fa-plus"></i> Insert New Item</a>
			</div>

		<?php



		} elseif ($do == 'add') {?>

		<h1 class="text-center" style="margin-top: 2em;color: #333">Insert New Item</h1>
			<div class="container">
				<form action="?do=insert" method="POST">
					<div class="form-group">
						<label for="name" class="col-form-label-lg">Name</label>
						<input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="Name of the Item" required>
					</div>
					<div class="form-group">
						<label for="description" class="col-form-label-lg">Description</label>
						<input type="text" name="description" class="form-control form-control-lg" id="description" placeholder="Type description for this Item" required>
					</div>
					<div class="form-group">
						<label for="price" class="col-form-label-lg">Price</label>
						<input type="number" name="price" class="form-control form-control-lg" id="price" placeholder="Price of the item" required>
					</div>
					<div class="form-group">
						<label for="made" class="col-form-label-lg">Country Made</label>
						<input type="text" name="made" class="form-control form-control-lg" id="made" placeholder="Type Country Made" required>
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label-lg">Status Of The Item</label>
						<select class='form-control form-control-lg' name='status'>
							<option value="0"></option>
							<option value="1">New</option>
							<option value="2">Like New</option>
							<option value="3">Used</option>
							<option value="4">Old</option>
						</select>
					</div>
					<div class="form-group">
						<label for="category" class="col-form-label-lg">Category Name</label>
						<select class='form-control form-control-lg' name='category'>
							<option value="0"></option>
							<?php
								$stmt = $connect->prepare('SELECT * FROM categories');
								$stmt->execute();
								$cats = $stmt->fetchAll();

								foreach ($cats as $cat) {
									echo '<option value="' . $cat['ID'] . '">'. $cat['Name'] . '</option>';
								}


							?>
						</select>
					</div>
					<div class="form-group">
						<label for="member" class="col-form-label-lg">Member Username</label>
						<select class='form-control form-control-lg' name='member'>
							<option value="0"></option>
							<?php
								$stmt = $connect->prepare('SELECT * FROM users');
								$stmt->execute();
								$users = $stmt->fetchAll();

								foreach ($users as $user) {
									echo '<option value="' . $user['UserID'] . '">'. $user['Username'] . '</option>';
								}


							?>
						</select>
					</div>

					<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin: 2em 0;">Add New Item</button>
				</form>
			</div>

		<?php
		} elseif ($do == 'insert') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$name = $_POST['name'];
				$desc = $_POST['description'];
				$price = $_POST['price'];
				$country = $_POST['made'];
				$status = $_POST['status'];
				$cat = $_POST['category'];
				$member = $_POST['member'];


				$errrArr = array();

				if (empty($name)) {
					$errrArr[] = "Name Field Can't be empty";
				}
				if (empty($desc)) {
					$errrArr[] = "Description Field Can't be empty";
				}
				if (empty($price) || $price <= 0) {
					$errrArr[] = "Price Field Can't be empty Or Smaller Than Or Equal 0 Or ";
				}
				if (empty($country)) {
					$errrArr[] = "Country Made Field Can't be empty";
				}
				if ($status == 0) {
					$errrArr[] = "Status Field Can't be empty";
				}
				if ($cat == 0) {
					$errrArr[] = "Status Field Can't be empty";
				}
				if ($member == 0) {
					$errrArr[] = "Status Field Can't be empty";
				}
				if (empty($errrArr)) {
					$stmt = $connect->prepare('INSERT INTO items(Name, Description, Price, Add_Date, Country_Made, Status, Cat_ID, Member_ID) VALUES(?, ?, ?, NOW(), ?, ?, ?, ?)');
					$stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member));
					
					header('Location: items.php');
					exit();
				} else {
					echo "<div class='container'>";
					echo "<h1 class='text-center' style='margin: 2em 0;color: #333'>Error While Inserting</h1>";
					foreach ($errrArr as $error) {
						echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
					}
					echo "</div>";
					errorAndRedirect('Read The Errors Carfally and Fix Them', 'back', 5);
					exit();
				}

				
			} else {
				errorAndRedirect("Sorry, Your not allowed to get here :(");
			}
		} elseif ($do == 'edit') {
			$id = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']) : 0;
			$stmt = $connect->prepare('SELECT * FROM items WHERE item_ID = ?');
			$stmt->execute(array($id));
			$count = $stmt->rowCount();
			$item = $stmt->fetch();
			if ($count > 0) { ?>
				
				<h1 class="text-center" style="margin-top: 2em;color: #333">Edit Item</h1>
				<div class="container">
				<form action="?do=update" method="POST">
					<input name="id" type="hidden" value="<?php echo $id; ?>">
					<div class="form-group">
						<label for="name" class="col-form-label-lg">Name</label>
						<input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="Name of the Item" value='<?php echo $item['Name'] ?>' required>
					</div>
					<div class="form-group">
						<label for="description" class="col-form-label-lg">Description</label>
						<input type="text" name="description" class="form-control form-control-lg" id="description" placeholder="Type description for this Item" value='<?php echo $item['Description'] ?>' required>
					</div>
					<div class="form-group">
						<label for="price" class="col-form-label-lg">Price</label>
						<input type="number" name="price" class="form-control form-control-lg" id="price" placeholder="Price of the item" value='<?php echo $item['Price'] ?>' required>
					</div>
					<div class="form-group">
						<label for="made" class="col-form-label-lg">Country Made</label>
						<input type="text" name="made" class="form-control form-control-lg" id="made" placeholder="Type Country Made" value='<?php echo $item['Country_Made'] ?>' required>
					</div>
					<div class="form-group">
						<label for="status" class="col-form-label-lg">Status Of The Item</label>
						<select class='form-control form-control-lg' name='status'>
							<option value="1" <?php if ($item['Status'] == 1) echo 'selected' ?> >New</option>
							<option value="2" <?php if ($item['Status'] == 2) echo 'selected' ?> >Like New</option>
							<option value="3" <?php if ($item['Status'] == 3) echo 'selected' ?> >Used</option>
							<option value="4" <?php if ($item['Status'] == 4) echo 'selected' ?> >Old</option>
						</select>
					</div>
					<div class="form-group">
						<label for="category" class="col-form-label-lg">Category Name</label>
						<select class='form-control form-control-lg' name='category'>
							<?php
								$stmt = $connect->prepare('SELECT * FROM categories');
								$stmt->execute();
								$cats = $stmt->fetchAll();

								foreach ($cats as $cat) {
									echo '<option value="' . $cat['ID'] . '"';
									if ($item['Cat_ID'] == $cat['ID']) {
										echo 'selected';
									}
									echo '>'. $cat['Name'] . '</option>';
								}


							?>
						</select>
					</div>
					<div class="form-group">
						<label for="member" class="col-form-label-lg">Member Username</label>
						<select class='form-control form-control-lg' name='member'>
							<?php
								$stmt = $connect->prepare('SELECT * FROM users');
								$stmt->execute();
								$users = $stmt->fetchAll();
								$select = '';
								foreach ($users as $user) {
									echo '<option value="' . $user['UserID'] . '"';
									if ($item['Member_ID'] == $user['UserID']) {
										echo 'selected';
									}
									echo '>'. $user['Username'] . '</option>';
								}


							?>
						</select>
					</div>
				<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin: 2em 0;">Save Changes</button>
				</div>
			</form>

			<?php 

			} else {
				errorAndRedirect('There Is Something Wrong Happend :(');
			}
		} elseif ($do == 'update') {
			


			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = $_POST['id'];
				$name = $_POST['name'];
				$desc = $_POST['description'];
				$price = $_POST['price'];
				$country = $_POST['made'];
				$status = $_POST['status'];
				$cat = $_POST['category'];
				$member = $_POST['member'];


				$errrArr = array();

				if (empty($name)) {
					$errrArr[] = "Name Field Can't be empty";
				}
				if (empty($desc)) {
					$errrArr[] = "Description Field Can't be empty";
				}
				if (empty($price) || $price <= 0) {
					$errrArr[] = "Price Field Can't be empty Or Smaller Than Or Equal 0 Or ";
				}
				if (empty($country)) {
					$errrArr[] = "Country Made Field Can't be empty";
				}
				if ($status == 0) {
					$errrArr[] = "Status Field Can't be empty";
				}
				if ($cat == 0) {
					$errrArr[] = "Status Field Can't be empty";
				}
				if ($member == 0) {
					$errrArr[] = "Status Field Can't be empty";
				}
				if (empty($errrArr)) {
					$stmt = $connect->prepare('UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Cat_ID = ?, Member_ID = ? WHERE item_ID = ?');
					$stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $id));
					
					header('Location: items.php');
					exit();
				} else {
					echo "<div class='container'>";
					echo "<h1 class='text-center' style='margin: 2em 0;color: #333'>Error While Updating</h1>";
					foreach ($errrArr as $error) {
						echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
					}
					echo "</div>";
					errorAndRedirect('Read The Errors Carfally and Fix Them', 'back', 5);
					exit();
				}

				
			} else {
				errorAndRedirect("Sorry, Your not allowed to get here :(");
			}




		} elseif ($do == 'delete') {
			$id = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']) : 0;
			$count = checkItem('item_ID', 'items', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('DELETE FROM items WHERE item_ID = ?');
				$stmt->execute(array($id));
				header('Location: items.php');
			} else {
				errorAndRedirect("Sorry, The ID Not Exist :(");
			}
		} elseif ($do == 'approve') {
			$id = (isset($_GET['itemid']) && is_numeric($_GET['itemid']))? intval($_GET['itemid']) : 0;
			$count = checkItem('item_ID', 'items', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('UPDATE items SET Approval = 1 WHERE item_ID = ?');
				$stmt->execute(array($id));
				header('Location: ' . $_SERVER['HTTP_REFERER']);
			} else {
				errorAndRedirect("Sorry, The ID Not Exist :(");
			}
		}

		include $templates . 'footer.php';
	}


	ob_end_flush();
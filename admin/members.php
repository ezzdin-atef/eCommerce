<?php

	session_start();

	$pageTitle = 'Members Page';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = (isset($_GET['do']))? $_GET['do'] : 'Manage';


		if ($do == 'Manage') { 

			$query = '';

			if ( isset($_GET['page']) && $_GET['page'] == 'pending' ) {
				$query = ' AND RegStatus = 0';
			}

			$stmt = $connect->prepare('SELECT * FROM users WHERE GroupID != 1' . $query);
			$stmt->execute();
			$rows = $stmt->fetchAll();

			?>
			<div class="container">
				<h1 class="text-center" style="margin: 2em 0 1em 0;color: #333">Manage Members</h1>

				<table class="table table-bordered main-table">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col">#ID</th>
				      <th scope="col">Full Name</th>
				      <th scope="col">Username</th>
				      <th scope="col">Email</th>
				      <th scope="col">Registerd Date</th>
				      <th scope="col">Control</th>
				    </tr>
				  </thead>
				  <tbody>
				  <?php 
				  	
				  	foreach ($rows as $row) {
				  		echo "<tr>";
				  		echo '<th>' . $row['UserID'] . '</th>';
				  		echo '<td>' . $row['FullName'] . '</td>';
				  		echo '<td>' . $row['Username'] . '</td>';
				  		echo '<td>' . $row['Email'] . '</td>';
				  		echo '<td>' . $row['Date'] . '</td>';
				  		echo '<td><a href="?do=edit&id='. $row['UserID'] .'" class="btn btn-primary"><i class="far fa-edit"></i> Edit</a>
				  					<a href="?do=delete&id='. $row['UserID'] .'"  class="btn btn-danger confirm"><i class="far fa-trash-alt"></i> Remove</a>';
				  		

				  		if ($row['RegStatus'] == 0) {
				  			echo ' <a href="?do=active&id='. $row['UserID'] .'" class="btn btn-info"><i class="fas fa-check"></i> Active</a>';
				  		}
				  		echo "</td></tr>";

				  	}
				  	
				  ?>
				  </tbody>
				</table>





				<a href="members.php?do=insert" class="btn btn-success"><i class="fas fa-plus"></i> Insert New Member</a>
			</div>

		<?php
		}elseif ($do == 'insert') { ?>
			
			<h1 class="text-center" style="margin-top: 2em;color: #333">Insert New Mamber</h1>
			<div class="container">
				<form action="?do=add" method="POST">
					<div class="form-group">
						<label for="name" class="col-form-label-lg">Full Name</label>
						<input type="text" name="FullName" class="form-control form-control-lg" id="name" placeholder="Typee Full Name" required>
					</div>
					<div class="form-group">
						<label for="username" class="col-form-label-lg">Username</label>
						<input type="text" name="Username" class="form-control form-control-lg" id="username" placeholder="Type Username" required>
					</div>
					<div class="form-group">
						<label for="email" class="col-form-label-lg">Email</label>
						<input type="email" name="Email" class="form-control form-control-lg" id="email" placeholder="Type Email" autocomplete="off" required>
					</div>
					<div class="form-group">
						<label for="password" class="col-form-label-lg">Password</label>
						<input type="password" name="Password" class="form-control form-control-lg" id="password" autocomplete="new-password" placeholder="Type Password" required="">
					</div>
					<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 2em">Add New Member</button>
				</form>
			</div>

		<?php
		} elseif ($do == 'add') {
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$fullName = $_POST['FullName'];
				$user = $_POST['Username'];
				$email = $_POST['Email'];
				$pass = $_POST['Password'];

				$errrArr = array();

				if (empty($fullName)) {
					$errrArr[] = "Full Name Field Can't be empty";
				}
				if (empty($user)) {
					$errrArr[] = "Username Field Can't be empty";
				}
				if (empty($email)) {
					$errrArr[] = "Email Field Can't be empty";
				}
				if (empty($pass)) {
					$errrArr[] = "Password Field Can't be empty";
				}

				if (empty($errrArr)) {
					
					if (checkItem('Username', 'users', $user) > 0) {
						errorAndRedirect('Exist');
					} else {
						$pass = sha1($pass);
						$stmt = $connect->prepare('INSERT INTO users(FullName, Username, Password, Email, RegStatus, Date) VALUES(?, ?, ?, ?, 1, NOW())');
						$stmt->execute(array($fullName, $user, $pass, $email));
						
						header('Location: members.php');
						exit();
						}
					
				} else {
					echo "<div class='container'>";
					echo "<h1 class='text-center' style='margin: 2em 0;color: #333'>Error While Updating</h1>";
					foreach ($errrArr as $error) {
						echo "<div class='alert alert-danger' role='alert'>" . $error . "</div>";
					}
					echo "</div>";
					header('refresh:2;url=?do=insert');
					exit();

				}

				
			} else {
				errorAndRedirect("Sorry, Your not allowed to get here :(");
			}


		} elseif ($do == 'edit') {
			$id = (isset($_GET['id']) && is_numeric($_GET['id']))? intval($_GET['id']) : 0;
			$stmt = $connect->prepare('SELECT * FROM users WHERE UserID = ?');
			$stmt->execute(array($id));
			$count = $stmt->rowCount();
			$row = $stmt->fetch();
			if ($count > 0) { ?>
				
				<h1 class="text-center" style="margin-top: 2em;color: #333">Edit Profile</h1>
				<div class="container">
					<form action="?do=update" method="POST">
						<input name="id" type="hidden" value="<?php echo $id; ?>">
						<div class="form-group">
							<label for="name" class="col-form-label-lg">Full Name</label>
							<input type="text" name="FullName" value="<?php echo $row['FullName'] ?>" class="form-control form-control-lg" id="name" required>
						</div>
						<div class="form-group">
							<label for="username" class="col-form-label-lg">Username</label>
							<input type="text" name="Username" value="<?php echo $row['Username'] ?>" class="form-control form-control-lg" id="username" required>
						</div>
						<div class="form-group">
							<label for="email" class="col-form-label-lg">Email</label>
							<input type="email" name="Email" value="<?php echo $row['Email'] ?>" class="form-control form-control-lg" id="email" autocomplete="off" required>
						</div>
						<div class="form-group">
							<label for="password" class="col-form-label-lg">Password</label>
							<input type="password" name="Password" class="form-control form-control-lg" id="password" autocomplete="new-password">
						</div>
						<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin-top: 2em">Submit</button>
					</form>
				</div>

			<?php 

			} else {
				errorAndRedirect('There Is Something Wrong Happend :(');
			}
		} elseif ($do == 'update') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = $_POST['id'];
				$fullName = $_POST['FullName'];
				$user = $_POST['Username'];
				$email = $_POST['Email'];
				$pass = $_POST['Password'];

				$errrArr = array();

				if (empty($fullName)) {
					$errrArr[] = "Full Name Field Can't be empty";
				}
				if (empty($user)) {
					$errrArr[] = "Username Field Can't be empty";
				}
				if (empty($email)) {
					$errrArr[] = "Email Field Can't be empty";
				}

				if (empty($errrArr)) {
					if (checkItem('Username', 'users', $user) > 0) {
						errorAndRedirect('Exist');
					} else {
						if (empty($pass)) {
							$stmt = $connect->prepare('UPDATE users SET FullName = ?, Username = ?, Email = ? WHERE UserID = ?');
							$stmt->execute(array($fullName, $user, $email, $id));
						} else {
							$pass = sha1($pass);
							$stmt = $connect->prepare('UPDATE users SET FullName = ?, Username = ?, Email = ?, Password = ? WHERE UserID = ?');
							$stmt->execute(array($fullName, $user, $email,$pass, $id));
						}
						$_SESSION['Username'] = $user;
						header('Location: ?do=edit&id=' . $id);
						exit();
					}
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
			$id = (isset($_GET['id']) && is_numeric($_GET['id']))? intval($_GET['id']) : 0;
			$count = checkItem('UserID', 'users', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('DELETE FROM users WHERE UserID = ?');
				$stmt->execute(array($id));
				header('Location: members.php');
			}
		} elseif ($do == 'active') {
			$id = (isset($_GET['id']) && is_numeric($_GET['id']))? intval($_GET['id']) : 0;
			$count = checkItem('UserID', 'users', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('UPDATE users SET RegStatus = 1 WHERE UserID = ?');
				$stmt->execute(array($id));
				header('Location: members.php');
			}
		}

		include $templates . 'footer.php';

	} else {
		header('Location: index.php');
		exit();
	}


?>
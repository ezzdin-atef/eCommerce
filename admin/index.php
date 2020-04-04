<?php
	session_start();
	$noNavbar = '';
	$pageTitle = 'Login Page';
	if (isset($_SESSION['Username'])) {
		header('Location: dashboard.php');
	}
    include 'init.php';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

    	$username = $_POST['Username'];
    	$password = $_POST['Passsword'];
    	$hashpassword = sha1($password);

    	$stmt = $connect->prepare('SELECT UserID, Username, Password FROM users WHERE Username = ? AND Password = ? And GroupID = 1 LIMIT 1');
    	$stmt->execute(array($username, $hashpassword));
        $row = $stmt->fetch();
    	$count = $stmt->rowCount();

    	if ($count > 0) {
    		$_SESSION['Username'] = $username;
            $_SESSION['ID'] = $row['UserID'];
    		header('Location: dashboard.php');
    		exit();
    	} else {
    		echo "Sorry, Your Not Registered";
    	}



    }


?>

<form class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	<h1>Login Form</h1>
    <input type="text" name="Username" class="form-control" autocomplete="off" placeholder="Username">
    <input type="password" name="Passsword" class="form-control" autocomplete="new-password" placeholder="Password">
  	<input type="submit" name="login" value="login" class="btn btn-primary btn-block">
</form>

<?php include $templates . 'footer.php'; ?>
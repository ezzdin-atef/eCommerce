<?php

	session_start();

	include 'init.php';

?>



<div class="container">
	<div class="row">
		<div class="col">
			<form class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<h1>Sign In Form</h1>
			    <input type="text" name="Username" class="form-control" autocomplete="off" placeholder="Username">
			    <input type="password" name="Passsword" class="form-control" autocomplete="new-password" placeholder="Password">
			  	<input type="submit" name="login" value="SIGN IN" class="btn btn-primary btn-block">
			</form>
		</div>
		<div class="col">
			<form class="login-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
				<h1>Sign Up Form</h1>
				<input type="text" name="FullName" class="form-control" autocomplete="off" placeholder="Type Full Name">
			    <input type="text" name="Username" class="form-control" autocomplete="off" placeholder="Type Username">
			    <input type="email" name="Email" class="form-control" autocomplete="off" placeholder="Type Email">
			    <input type="password" name="Passsword" class="form-control" autocomplete="new-password" placeholder="Type Password">
			    <input type="password" name="Passsword" class="form-control" autocomplete="new-password" placeholder="Type Passsword Again">
			  	<input type="submit" name="login" value="SIGN UP" class="btn btn-primary btn-block">
			</form>
		</div>
	</div>
</div>













<?php include $templates . 'footer.php';?>
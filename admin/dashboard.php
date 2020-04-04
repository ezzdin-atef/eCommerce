<?php 

session_start();

if (isset($_SESSION['Username'])) {

	$pageTitle = 'Dashboard Page';

	include 'init.php';?>

	<div class="container dashboard-main">
		<h1 class="text-center">Dashboard</h1>
		<div class="row">
			<div class="col card" style="background-color: #2ecc71;">
				<h3>Total Members</h3>
				<span><a href='members.php'><?php echo countItem('UserID', 'users'); ?></a></span>
			</div>
			<div class="col card" style="background-color: #e74c3c;">
				<h3>Pending Members</h3>
				<span><a href="members.php?page=pending"><?php echo countItem('RegStatus', 'users', '0'); ?></a></span>
			</div>
			<div class="col card" style="background-color: #3498db;">
				<h3>Total Items</h3>
				<span><a href='items.php'><?php echo countItem('item_ID', 'items'); ?></a></span>
			</div>
			<div class="col card" style="background-color: #9b59b6;">
				<h3>Total Comments</h3>
				<span>320</span>
			</div>
		</div>
		<div class="row">
			<div class="col wide-card">
				<?php $limitLatest = 3 ?>
				<h3><i class="fas fa-user-friends"></i> Latest <?php $limitLatest ?> Registered Users</h3>
				<div class="wide-card-content">
					<ul class="list-group list-group-flush">
					<?php 
						$rows = getLatest('*', 'users', 'UserID', $limitLatest);
						foreach ($rows as $row) {
							echo "<li class='list-group-item'>" . $row['Username'] . "</li>";
						}
						/*

<ul class="list-group list-group-flush">
  <li class="list-group-item">Cras justo odio</li>
  <li class="list-group-item">Dapibus ac facilisis in</li>
  <li class="list-group-item">Morbi leo risus</li>
  <li class="list-group-item">Porta ac consectetur ac</li>
  <li class="list-group-item">Vestibulum at eros</li>
</ul>
						*/
					?>
					</ul>
				</div>
			</div>
			<div class="col wide-card">
				<?php $limitLatest = 3 ?>
				<h3><i class="fas fa-tags"></i> Latest <?php echo $limitLatest; ?> Items</h3>
				<div class="wide-card-content">
					<ul class="list-group list-group-flush">
						<?php
							$rows = getLatest('*', 'items', 'item_ID', $limitLatest);
							foreach ($rows as $row) {
								echo "<li class='list-group-item'>" . $row['Name'] . "</li>";
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>









	<?php
	include $templates . 'footer.php';


} else {
	header('Location: index.php');
	exit();
}






?>
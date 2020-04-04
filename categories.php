<?php
	session_start();

	$pageTitle = 'Categories';

	include 'init.php';

	?>


	<div class="container">
		<h1 class="text-center" style="margin: 2em 0 1em 0;color: #333">
			<?php echo str_replace('-', ' ', '[' . $_GET['catname']) . '] Category' ?>
		</h1>
	
		<div class="category-items">
			<?php
			foreach (getItems($_GET['catid']) as $item) {
				echo '<div class="item-card">';
					echo '<span>$'.$item['Price'].'</span>';
					echo '<img src="https://via.placeholder.com/300.png/545b62/fff" alt="...">';
					echo '<div class="card-content">';
						echo '<h3>'.$item['Name'].'</h3>';
						echo '<p>'.$item['Description'].'</p>';
					echo '</div>';
				echo '</div>';
			}
			?>
		</div>

	</div>









	<?php include $templates . 'footer.php'; ?>
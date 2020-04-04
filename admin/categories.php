<?php

	ob_start();

	session_start();
	$pageTitle = 'Categories';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = (isset($_GET['do']))? $_GET['do'] : 'manage';

		if ($do == 'manage') {
			$sort = 'ASC';
			$order = array('ASC', 'DESC');
			if (isset($_GET['order']) && in_array($_GET['order'], $order)) {
				$sort = $_GET['order'];
			}

			$stmt = $connect->prepare('SELECT * FROM categories ORDER BY Ordering '. $sort);
			$stmt->execute();
			$cats = $stmt->fetchAll();

			?>
			
			<div class="container">
				<h1 class="text-center" style="margin: 2em 0 1em 0;color: #333">Manage Categories</h1>
				<div class="options">
					<i class="fas fa-cogs"></i>
					<div class="options-content">
						<div style="margin-top: -2px;" class="sorting-option">
							<span>Ordering: </span>
							<a href="?order=ASC" class="<?php if($sort == 'ASC') echo 'active' ?>">ASC</a>
							<a href="?order=DESC" class="<?php if($sort == 'DESC') echo 'active' ?>">DESC</a>
						</div>
					</div>
				</div>
				<div class="category-cards">
					<?php foreach ($cats as $cat) {
						echo '<div class="category-card">';
							echo '<a href="?do=delete&catid='.$cat['ID'].'"><div class="close-icon confirm"><i class="fas fa-times"></i></div></a>';
							echo '<a href="?do=edit&catid='. $cat['ID'] .'"><div class="edit-icon"><i class="far fa-edit"></i></div></a>';
							echo '<h2>'.$cat['Name'].'</h2>';
							echo '<p>'.(($cat['Description'] == '')? 'There is no description for this category' : $cat['Description']).'</p>';
							echo '<div>';
								echo ($cat['Visibility']==0)?'<span class="disabled">Not Visibile</span>':'<span class="enabled">Visibile</span>';

								echo ($cat['Allow_Comment']==0)?'<span class="disabled">Comments Disabled</span>':'<span class="enabled">Comments Enabled</span>';

								echo ($cat['Allow_Ads']==0)?'<span class="disabled">Ads Disabled</span>':'<span class="enabled">Ads Enabled</span>';
							echo '</div>';
						echo '</div>';
					} ?>
				</div>

				<a href="?do=add" style="margin: 10px" class="btn btn-success"><i class="fas fa-plus"></i> Add New Category</a>
			</div>

			<?php

		} elseif ($do == 'add') {?>
			

			<h1 class="text-center" style="margin-top: 2em;color: #333">Insert New Category</h1>
			<div class="container">
				<form action="?do=insert" method="POST">
					<div class="form-group">
						<label for="name" class="col-form-label-lg">Name</label>
						<input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="Name of the category" required>
					</div>
					<div class="form-group">
						<label for="description" class="col-form-label-lg">Description</label>
						<input type="text" name="description" class="form-control form-control-lg" id="description" placeholder="Type description for this category">
					</div>
					<div class="form-group">
						<label for="email" class="col-form-label-lg">Ordering</label>
						<input type="text" name="ordering" class="form-control form-control-lg" id="ordering" placeholder="Type order for category" autocomplete="off">
					</div>
					<div class="form-group">
						<label class="col-form-label-lg">Visibility</label>
						<div class="form-check">
							<input type="radio" id="vis-yes" name="visibility" value="1" checked>
							<label for="vis-yes">Yes</label>
						</div>
						<div class="form-check">
							<input type="radio" id="vis-no" name="visibility" value="0">
							<label for="vis-no">No</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-form-label-lg">Allow Comment</label>
						<div class="form-check">
							<input type="radio" id="comment-yes" name="allow_comment" value="1" checked>
							<label for="comment-yes">Yes</label>
						</div>
						<div class="form-check">
							<input type="radio" id="comment-no" name="allow_comment" value="0">
							<label for="comment-no">No</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-form-label-lg">Allow Ads</label>
						<div class="form-check">
							<input type="radio" id="ads-yes" name="allow_ads" value="1" checked>
							<label for="ads-yes">Yes</label>
						</div>
						<div class="form-check">
							<input type="radio" id="ads-no" name="allow_ads" value="0">
							<label for="ads-no">No</label>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin: 2em 0;">Add New Category</button>
				</form>
			</div>

		<?php	
		} elseif ($do == 'insert') {
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				$name = $_POST['name'];
				$desc = $_POST['description'];
				$ordering = $_POST['ordering'];
				$visibility = $_POST['visibility'];
				$allow_comment = $_POST['allow_comment'];
				$allow_ads = $_POST['allow_ads'];

					
				if (checkItem('Name', 'categories', $name) > 0) {
					errorAndRedirect('Exist');
				} else {
					$stmt = $connect->prepare('INSERT INTO categories(Name, Description, ordering, visibility, Allow_Comment, Allow_Ads) VALUES(?, ?, ?, ?, ?, ?)');
					$stmt->execute(array($name, $desc, $ordering, $visibility, $allow_comment, $allow_ads));
					
					header('Location: categories.php');
					exit();
				}

			} else {
				errorAndRedirect("Sorry, Your not allowed to get here :(");
			}


		} elseif ($do == 'edit') {
			$id = (isset($_GET['catid']) && is_numeric($_GET['catid']))? intval($_GET['catid']) : 0;
			$stmt = $connect->prepare('SELECT * FROM categories WHERE ID = ?');
			$stmt->execute(array($id));
			$count = $stmt->rowCount();
			$row = $stmt->fetch();
			if ($count > 0) { ?>
				
				<h1 class="text-center" style="margin-top: 2em;color: #333">Edit Category</h1>
				<div class="container">
				<form action="?do=update" method="POST">
					<input name="id" type="hidden" value="<?php echo $id; ?>">
					<div class="form-group">
						<label for="name" class="col-form-label-lg">Name</label>
						<input type="text" name="name" class="form-control form-control-lg" id="name" placeholder="Name of the category" value="<?php echo $row['Name'] ?>" required>
					</div>
					<div class="form-group">
						<label for="description" class="col-form-label-lg">Description</label>
						<input type="text" name="description" class="form-control form-control-lg" id="description" placeholder="Type description for this category" value="<?php echo $row['Description'] ?>">
					</div>
					<div class="form-group">
						<label for="email" class="col-form-label-lg">Ordering</label>
						<input type="text" name="ordering" class="form-control form-control-lg" id="ordering" placeholder="Type order for category" value="<?php echo $row['Ordering'] ?>" autocomplete="off">
					</div>
					<div class="form-group">
						<label class="col-form-label-lg">Visibility</label>
						<div class="form-check">
							<input type="radio" id="vis-yes" name="visibility" value="1" 
								<?php if($row['Visibility'] == 1) echo 'checked'; ?>>
							<label for="vis-yes">Yes</label>
						</div>
						<div class="form-check">
							<input type="radio" id="vis-no" name="visibility" value="0"
								<?php if($row['Visibility'] == 0) echo 'checked'; ?>>
							<label for="vis-no">No</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-form-label-lg">Allow Comment</label>
						<div class="form-check">
							<input type="radio" id="comment-yes" name="allow_comment" value="1"
								<?php if($row['Allow_Comment'] == 1) echo 'checked'; ?>>
							<label for="comment-yes">Yes</label>
						</div>
						<div class="form-check">
							<input type="radio" id="comment-no" name="allow_comment" value="0"
								<?php if($row['Allow_Comment'] == 0) echo 'checked'; ?>>
							<label for="comment-no">No</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-form-label-lg">Allow Ads</label>
						<div class="form-check">
							<input type="radio" id="ads-yes" name="allow_ads" value="1"
								<?php if($row['Allow_Ads'] == 1) echo 'checked'; ?>>
							<label for="ads-yes">Yes</label>
						</div>
						<div class="form-check">
							<input type="radio" id="ads-no" name="allow_ads" value="0"
								<?php if($row['Allow_Ads'] == 0) echo 'checked'; ?>>
							<label for="ads-no">No</label>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-lg btn-block" style="margin: 2em 0;">Save Changes</button>
				</form>
				</div>

			<?php 

			} else {
				errorAndRedirect('There Is Something Wrong Happend :(');
			}
		} elseif ($do == 'update') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$id = $_POST['id'];
				$name = $_POST['name'];
				$desc = $_POST['description'];
				$ordering = $_POST['ordering'];
				$visibility = $_POST['visibility'];
				$commenting = $_POST['allow_comment'];
				$ads = $_POST['allow_ads'];

				$stmt = $connect->prepare('UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ?  WHERE ID = ?');
				$stmt->execute(array($name, $desc, $ordering, $visibility, $commenting, $ads, $id));
				header('Location: categories.php');
				exit();
			} else {
				errorAndRedirect("Sorry, Your not allowed to get here :(");
			}
		} elseif ($do == 'delete') {
			$id = (isset($_GET['catid']) && is_numeric($_GET['catid']))? intval($_GET['catid']) : 0;
			$count = checkItem('ID', 'categories', $id);
			if ($count > 0) {
				$stmt = $connect->prepare('DELETE FROM categories WHERE ID = ?');
				$stmt->execute(array($id));
				header('Location: categories.php');
			}
		}


		include $templates . 'footer.php';

	} else {
		header('Location: index.php');
		exit();
	}


	ob_end_flush();

?>
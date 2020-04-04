<?php



	function getTitle() {
		global $pageTitle;
		if (isset($pageTitle)) {
			echo $pageTitle;
		} else {
			echo 'Default Page';
		}
	}

	function errorAndRedirect($message, $place = 'back', $second = 3) {
		echo '<div class="container">';
		echo "<div class='alert alert-danger'>" . $message . "</div>";
		echo "<div class='alert alert-info'>You will be redirected after " . $second . " Seconds</div>";
		echo '</div>';
		if ($place == 'back' && isset($_SERVER['HTTP_REFERER'])) $place = $_SERVER['HTTP_REFERER'];
		else $place = 'index.php';
		header('refresh:'. $second .';url='.$place);
	}


	function checkItem($slct, $table_name, $value) {
		global $connect;
		$s = $connect->prepare('SELECT '.$slct.' FROM '.$table_name.' WHERE '.$slct.' = ?');
		$s->execute(array($value));
		$c = $s->rowCount();
		return $c;
	}


	function countItem($col, $table_name, $condition = '') {
		global $connect;
		if ($condition == '') { 
			$w = '';
		} else { 
			$w = ' WHERE ' . $col . ' = ' . $condition;
		}
		$s = $connect->prepare('SELECT COUNT('. $col .') FROM ' . $table_name . $w);
		$s->execute();
		return $s->fetchColumn();
	}


	function getLatest($select, $table_name, $order, $limit = 3) {

		global $connect;

		$s = $connect->prepare("SELECT $select FROM $table_name ORDER BY $order DESC LIMIT $limit");

		$s->execute();

		return $s->fetchAll();
	}
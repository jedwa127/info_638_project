<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Search the Collection");
	html_header();
	html_column_1();
	html_column_2();
	$search = strtolower(sanitize_string($_GET["search"]));
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	if (!isset($_GET["search"])) {
		die();
	}
	echo "Search results for \"$search\":<br>";
	// Search results for items
	$result = $pdo->query("SELECT * FROM items NATURAL JOIN media_types WHERE MATCH(item_name,item_desc) AGAINST('$search' IN BOOLEAN MODE) ORDER BY item_name");
	echo "<br>Items:<br><br>";
	if ($result->rowCount() == 0) {
		echo 'No results.<br><br>';
	} else {
		$num_results = $result->rowCount();
		if ($num_results == 1) {
			echo "$num_results result:<br><br>";
		} else {
			echo "$num_results results:<br><br>";
		}
		while ($row = $result->fetch()) {
			$item_id = html_entity_decode($row['item_id']);
			echo '<a href="item.php?item=' . $item_id . '">' . html_entity_decode($row['item_name']) . '</a><br>';
			echo 'Date: ' . html_entity_decode($row['item_date']) . '<br>';
			echo 'Description: ' . html_entity_decode($row['item_desc']) . '<br>';
			echo 'Media Type: ' . html_entity_decode($row['media_type']) . '<br><br>';
		}
	}
	// Search results for creators
	$result = $pdo->query("SELECT * FROM creators NATURAL JOIN creator_types WHERE MATCH(creator_name,creator_desc) AGAINST('$search' IN BOOLEAN MODE) ORDER BY creator_name");
	echo "Creators:<br><br>";
	if ($result->rowCount() == 0) {
		echo 'No results.<br><br>';
	} else {
		$num_results = $result->rowCount();
		if ($num_results == 1) {
			echo "$num_results result:<br><br>";
		} else {
			echo "$num_results results:<br><br>";
		}
		while ($row = $result->fetch()) {
			$creator_id = html_entity_decode($row['creator_id']);
			echo '<a href="creator.php?creator=' . $creator_id . '">' . html_entity_decode($row['creator_name']) . '</a><br>';
			echo 'Type: ' . html_entity_decode($row['creator_type']) . '<br>';
			echo 'Description: ' . html_entity_decode($row['creator_desc']) . '<br><br>';
		}
	}
	html_column_3();
	html_footer();

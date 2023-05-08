<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Browse Items");
	html_header();
	html_column_1();
	html_column_2();
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	$result = $pdo->query("SELECT * FROM items NATURAL JOIN media_types ORDER BY item_name");
	if ($result->rowCount() == 0) {
		echo 'No items.<br><br>';
	} else {
		while ($row = $result->fetch()) {
			$item_id = htmlspecialchars($row['item_id']);
			echo '<a href="item.php?item=' . $item_id . '">' . html_entity_decode($row['item_name']) . '</a><br>';
			echo 'Date: ' . html_entity_decode($row['item_date']) . '<br>';
			echo 'Description: ' . html_entity_decode($row['item_desc']) . '<br>';
			echo 'Media Type: ' . html_entity_decode($row['media_type']) . '<br><br>';
		}
	}
	html_column_3();
	html_footer();

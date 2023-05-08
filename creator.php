<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	$creator_id = $_GET['creator'];
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}			
	$result = $pdo->query("SELECT creator_name FROM creators WHERE creator_id = $creator_id");
	while ($row = $result->fetch()) {
		$creator_name = html_entity_decode($row['creator_name']);
	}
	html_head("Creator: $creator_name");
	html_header();	
	html_column_1();
	html_column_2();
	$user_id = htmlspecialchars($_SESSION['user_id']);
	$result = $pdo->query("SELECT * FROM creators NATURAL JOIN creator_types WHERE creator_id = $creator_id");
	while ($row = $result->fetch()) {
		$creator_name =  html_entity_decode($row['creator_name']);
		echo ucfirst(html_entity_decode($row['creator_name'])) . '<br>';
		echo 'Type: ' . ucfirst(html_entity_decode($row['creator_type'])) . '<br>';
		echo 'Description: ' . ucfirst(html_entity_decode($row['creator_desc'])) . '<br><br>';
	}
	echo "Attributed items:<br><br>";
	$result = $pdo->query("SELECT item_name,item_id FROM creators_items NATURAL JOIN items WHERE creator_id = $creator_id");
	if ($result->rowCount() == 0) {
		echo 'No items.<br><br>';
	} else {
		while ($row = $result->fetch()) {
			$item_id = html_entity_decode($row['item_id']);
			echo '<a href="item.php?item=' . $item_id . '">' . htmlspecialchars($row['item_name']) . '</a><br>';
		}
	}
	html_column_3();
	html_footer();

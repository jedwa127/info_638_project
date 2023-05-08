<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Home");
	html_header();
	html_column_1();
	html_column_2();
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	$result = $pdo->query("SELECT COUNT(item_name) FROM items");
	while ($row = $result->fetch()) {
		$item_count = html_entity_decode($row['COUNT(item_name)']);
	}
	$result = $pdo->query("SELECT COUNT(creator_name) FROM creators");
	while ($row = $result->fetch()) {
		$creator_count = html_entity_decode($row['COUNT(creator_name)']);
	}
	echo "Welcome to a basic CMS. Using this platform, users can upload items, create records for the creators of items, and browse and search existing items.<br><br>";
	echo "There are currently $item_count items and " .  $creator_count-2 . " creators in the collection.";
	html_column_3();
	html_footer();

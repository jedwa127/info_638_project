<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	$item_id = $_GET['item'];
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	$result = $pdo->query("SELECT item_name FROM items WHERE item_id = $item_id");
	while ($row = $result->fetch()) {
		$item_name = html_entity_decode($row['item_name']);
	}
	html_head("Item: $item_name");
	html_header();
	html_column_1();
	html_column_2();
	$result = $pdo->query("SELECT * FROM items NATURAL JOIN media_types WHERE item_id = $item_id");
	while ($row = $result->fetch()) {
		echo html_entity_decode($row['item_name']) . '<br>';
		echo 'Date: ' . html_entity_decode($row['item_date']) . '<br>';
		echo 'Description: ' . html_entity_decode($row['item_desc']) . '<br>';
		echo 'Media type: ' . html_entity_decode($row['media_type']) . '<br>';
		$user_id = html_entity_decode($row['uploaded_by']);
	}
	$result = $pdo->query("SELECT user_name FROM users WHERE user_id = $user_id");
	while ($row = $result->fetch()) {
		echo 'Uploaded by: ' . html_entity_decode($row['user_name']) . '<br><br>';
	}
	$result = $pdo->query("SELECT file_path,file_type_id FROM files NATURAL JOIN items WHERE item_id = $item_id");
	while ($row = $result->fetch()) {
		$file_path = html_entity_decode($row['file_path']);
		$file_type_id = html_entity_decode($row['file_type_id']);
	}
	if ($file_type_id = 1) {
		pdf_embed($file_path);
	} elseif ($file_type_id = 2 || 3) {
		image_embed($file_path);
	} elseif ($file_type_id = 4 || 5) {
		audio_embed($file_path);
	} elseif ($file_type_id = 6 || 7 || 8) {
		video_embed($file_path);
	}
	html_column_3();
	html_footer();

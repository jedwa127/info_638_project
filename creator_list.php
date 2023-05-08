<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Browse Creators");
	html_header();
	html_column_1();
	html_column_2();
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	$result = $pdo->query("SELECT * FROM creators NATURAL JOIN creator_types ORDER BY creator_name");
	if ($result->rowCount() == 0) {
		echo 'No creators.<br><br>';
	} else {
	while ($row = $result->fetch()) {
			$creator_id = html_entity_decode($row['creator_id']);
			echo '<a href="creator.php?creator=' . $creator_id . '">' . ucfirst(htmlspecialchars($row['creator_name'])) . '</a><br>';
			echo 'Type: ' . ucfirst(html_entity_decode($row['creator_type'])) . '<br>';
			echo 'Description: ' . html_entity_decode($row['creator_desc']) . '<br><br>';
		}
	}
	html_column_3();
	html_footer();

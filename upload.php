<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("File Upload");
	html_header();
	html_column_1();
	html_column_2();
	$user_id = htmlspecialchars($_SESSION['user_id']);
	$file = $_FILES['file_upload'];
	$file_name = $_FILES['file_upload']['name'];
	$file_temp_path = $_FILES['file_upload']['tmp_name'];
	$file_size = filesize($file_temp_path);
	$file_error = $_FILES['file_upload']['error'];
	$file_ext_orig = strtolower(end(explode('.', $file_name)));
	$file_mime = finfo_open(FILEINFO_MIME_TYPE);
	$file_type = finfo_file($file_mime, $file_temp_path);
	$name = sanitize_string($_POST["item_name"]);
	$creator = sanitize_string($_POST["item_creator"]);
	$date = sanitize_string($_POST["item_date"]);
	$desc = sanitize_string($_POST["item_desc"]);
	$type = sanitize_string($_POST["item_type"]);	
	if ($file_ext_orig == "jpeg") {
		$file_ext_fixed = "jpg";
	} else {
		$file_ext_fixed = $file_ext_orig;
	}
	if (!isset($_FILES["file_upload"])) {
		die("There is no file to upload.");
	}
	if (empty($name) || empty($creator) || empty($date) || empty($desc) || empty($type)) {
		die("Please fill out all fields.");
	}
	if ($file_error !== 0) {
		die("File upload error.");
	}
	if ($file_size === 0) {
		die("The file is empty.");
	}
	if ($file_size >= 1073741824) {
		die("File too large, file must be less than 1 GB.");
	}
	$file_allowed = [
					'application/pdf' => 'pdf',
					'image/jpeg' => 'jpg',
					'image/png' => 'png',
					'audio/mpeg' => 'mp3',
					'audio/wav' => 'wav',
					'video/mp4' => 'mp4',
					'video/webm' => 'webm',
					'video/ogg' => 'ogg'
					];
	if(!in_array($file_type, array_keys($file_allowed))) {
		die("Unacceptable file type, file must be .pdf, .jpg, .png, .mp3, .wav, .mp4, .webm, or .ogg.");
	}
	$file_new_name = uniqid('', true);
	$file_destination = "uploads/" . $file_new_name . "." . $file_ext_fixed;
	if (!copy($file_temp_path, $file_destination)) {
		die("Unable to upload file.");
	}
	// SQL
	try {
		$pdo = new PDO($attr, $user, $pass, $opts);
	}
	catch (PDOException $e) {
		throw new PDOException($e->getMessage(), (int)$e->getCode());
	}
	// Insert into file database
	$result = $pdo->query("SELECT file_type_id FROM file_types WHERE mime_type='$file_type'");
	while ($row = $result->fetch()) {
		$file_type_id = $row['file_type_id'];
	}
	$stmt = $pdo->prepare('INSERT INTO files(file_id, file_path, file_type_id, file_size, file_upload_date) VALUES(?,?,?,?,?)');
	$stmt->bindParam(2, $file_destination, PDO::PARAM_STR, 512);
	$stmt->bindParam(3, $file_type_id, PDO::PARAM_INT);
	$stmt->bindParam(4, $file_size, PDO::PARAM_INT);
	$stmt->execute([NULL, $file_destination, $file_type_id, $file_size, NULL]);
	// Insert into item database
	$file_id = $pdo->lastInsertId();
	$stmt = $pdo->prepare('INSERT INTO items(item_id, item_name, item_date, item_desc, media_type_id, file_id, uploaded_by) VALUES(?,?,?,?,?,?,?)');
	$stmt->bindParam(2, $name, PDO::PARAM_STR, 512);
	$stmt->bindParam(3, $date, PDO::PARAM_STR, 32);
	$stmt->bindParam(4, $desc, PDO::PARAM_STR, 512);
	$stmt->bindParam(5, $type, PDO::PARAM_INT);
	$stmt->bindParam(6, $file_id, PDO::PARAM_INT);
	$stmt->bindParam(7, $user_id, PDO::PARAM_INT);
	$stmt->execute([NULL, $name, $date, $desc, $type, $file_id, $user_id]);
	// Insert into creator_item database
	$item_id = $pdo->lastInsertId();
	$result = $pdo->query("SELECT creator_id FROM creators WHERE creator_name='$creator'");
	while ($row = $result->fetch()) {
		$creator_id = $row['creator_id'];
	}
	$stmt = $pdo->prepare('INSERT INTO creators_items(creator_item_id, creator_id, item_id) VALUES(?,?,?)');
	$stmt->bindParam(2, $creator_id, PDO::PARAM_INT);
	$stmt->bindParam(3, $item_id, PDO::PARAM_INT);
	$stmt->execute([NULL, $creator_id, $item_id]);
	unlink($file_temp_path);
	echo "File \"$file_name\" uploaded successfully.";
	html_column_3();
	html_footer();

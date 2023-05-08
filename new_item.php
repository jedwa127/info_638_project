<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Upload an Item");
	html_header();
	html_column_1();
	html_column_2();
	if (!isset($_SESSION['user_name'])) {
		echo "You must be logged in to upload an item.";
	} else {
		echo "Select a file to upload. Accepted file types are .pdf, .jpg, .png, .mp3, .wav, .mp4, .webm, or .ogg.";
		echo '
				<form action="upload.php" method="POST" enctype="multipart/form-data">
					<input type="file" id="file_upload" name="file_upload"><br> <!-- File upload field -->
					<label for="item_name">Name:</label> <!-- Name field -->
					<input type="text" id="item_name" name="item_name"><br>
					<label for="item_creator">Creator:</label> <!-- Item creator field -->
						<select name="item_creator" id="item_creator">
			';
		try {
			$pdo = new PDO($attr, $user, $pass, $opts);
		}
		catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}			
		$query = "SELECT creator_name FROM creators";
		$result = $pdo->query($query);
		while ($row = $result->fetch()) {
			$creator_name = htmlspecialchars($row['creator_name']);
			echo '<option value="' . $creator_name . '">' . $creator_name . '</option>';
		}
		echo '
						</select><br>
					<label for="item_date">Date:</label> <!-- Date created field -->
					<input type="date" id="item_date" name="item_date"><br>
					<label for="item_desc">Description:</label> <!-- Description field -->
					<input type="text" id="item_desc" name="item_desc"><br>
					<label for="item_type">Media type:</label> <!-- Item type field -->
						<select name="item_type" id="item_type">
			';
		$query = "SELECT media_type_id, media_type FROM media_types";
		$result = $pdo->query($query);
		while ($row = $result->fetch()) {
			$media_type_id = htmlspecialchars($row['media_type_id']);
			$media_type = htmlspecialchars($row['media_type']);
			echo '<option value="' . $media_type_id . '">' . $media_type . '</option>';
		}
		echo '
						</select><br>
					<input type="submit" name="submit" value="Upload"><br> <!-- Upload button -->
				</form>
			';
	}
	html_column_3();
	html_footer();

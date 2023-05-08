<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Add a Creator");
	html_header();
	html_column_1();
	html_column_2();
	if (!isset($_SESSION['user_name'])) {
		echo "You must be logged in to add a creater.";
	} else {
		echo '
				<form action="" method="POST">
					<label for="creator_name">Name:</label> <!-- Name field -->
					<input type="text" id="creator_name" name="creator_name"><br>
					<label for="creator_desc">Description:</label> <!-- Description field -->
					<input type="text" id="creator_desc" name="creator_desc"><br>
					<label for="creator_type_id">Type:</label> <!-- Type field -->
					<select name="creator_type" id="creator_type">
			';	
		try {
			$pdo = new PDO($attr, $user, $pass, $opts);
		}
		catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
		$result = $pdo->query("SELECT creator_type,creator_type_id FROM creator_types WHERE creator_type_id <> 1 AND creator_type_id <> 2");
		while ($row = $result->fetch()) {
			$creator_type = htmlspecialchars($row['creator_type']);
			$creator_type_id = htmlspecialchars($row['creator_type_id']);
			echo '<option value="' . $creator_type_id . '">' . $creator_type . '</option>';
		}
		echo '
					</select><br>
					<input type="submit" name="add" value="Add"><br> <!-- Add button -->
				</form>
			';
		if (isset($_POST["add"])) {
			$user_id = htmlspecialchars($_SESSION['user_id']);
			$creator_name = $_POST["creator_name"];
			$creator_desc = $_POST["creator_desc"];
			$creator_type_id = $_POST["creator_type"];
			$creators = array_map('strtolower', $pdo->query('SELECT creator_name FROM creators')->fetchAll(PDO::FETCH_COLUMN));		
			if (!empty($creator_name) && !empty($creator_desc)) {
				if(in_array(strtolower($creator_name), $creators)) {
					echo "Creator already exists.";
				} else {
					$stmt = $pdo->prepare('INSERT INTO creators(creator_id,creator_name,creator_desc,creator_type_id, uploaded_by) VALUES(?,?,?,?,?)');
					$stmt->bindParam(2, $creator_name, PDO::PARAM_STR, 512);
					$stmt->bindParam(3, $creator_desc, PDO::PARAM_STR, 512);
					$stmt->bindParam(4, $creator_type_id, PDO::PARAM_INT);
					$stmt->bindParam(5, $user_id, PDO::PARAM_INT);
					$stmt->execute([NULL, $creator_name, $creator_desc, $creator_type_id, $user_id]);
					echo "Creator added.";
					}
			} else {
				echo "Please enter a creator name and description.";
			}
		}
	}
	html_column_3();
	html_footer();

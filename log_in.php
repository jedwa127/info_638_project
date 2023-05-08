<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Log In");
	html_header();
	html_column_1();
	html_column_2();
	if (!isset($_SESSION['user_name'])) {
		echo '
			<form action="" method="POST">
				<label for="user_name">Username:</label> <!-- Username field -->
				<input type="text" id="user_name" name="user_name"><br>
				<label for="user_pword">Password:</label> <!-- Password field -->
				<input type="password" id="user_pword" name="user_pword"><br>
				<input type="submit" name="log_in" value="Log In"><br> <!-- Log in button -->
			</form>
			';
		try {
			$pdo = new PDO($attr, $user, $pass, $opts);
		}
		catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
		if (!isset($_POST["log_in"])) {
		} else {
			$un_temp = sanitize_string($_POST["user_name"]);
			$pw_temp = sanitize_string($_POST["user_pword"]);
			$query = "SELECT * FROM users WHERE user_name = '$un_temp'";
			$result = $pdo->query($query);
			if (!$result->rowCount()) {
				die("User not found");
			}
			$row = $result->fetch();
			$uid = $row['user_id'];
			$un = $row['user_name'];
			$pw = $row['user_pword'];
			if (password_verify(str_replace("'", "", $pw_temp), $pw)) {
				$_SESSION['user_name'] = $un;
				$_SESSION['user_id'] = $uid;
				$previous = $_SERVER['HTTP_REFERER'];
				header("Location: $previous");
				die();
			} else {
				die("Incorrect password.");
			}
		}
	} else {
		current_login();
	}
	html_column_3();
	html_footer();

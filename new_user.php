<?php
	session_start();
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	html_head("Register an Account");
	html_header();
	html_column_1();
	html_column_2();
	if (!isset($_SESSION['user_name'])) {
		echo '
				<form action="" method="POST">
					<label for="username">Username:</label> <!-- Username field -->
					<input type="text" id="username" name="username"><br>
					<label for="password">Password:</label> <!-- Password field -->
					<input type="password" id="password" name="password"><br>
					<label for="re_password">Re-enter password:</label> <!-- Re-enter password field -->
					<input type="password" id="re_password" name="re_password"><br>
					<input type="submit" name="register" value="Register"><br> <!-- Submit button -->
				</form>
			';
		try {
			$pdo = new PDO($attr, $user, $pass, $opts);
		}
		catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int)$e->getCode());
		}
		if (!isset($_POST["register"])) {
		} else {
			$username = sanitize_string($_POST["username"]);
			$password = sanitize_string($_POST["password"]);
			$re_password = sanitize_string($_POST["re_password"]);
			$users = array_map('strtolower', $pdo->query('SELECT user_name FROM users')->fetchAll(PDO::FETCH_COLUMN));
			if (preg_match('/^[a-zA-Z0-9_.-]*$/', $username) == 1) {
				die("Username may contain only letters, numbers, hyphens, and underscores without spaces.");
			}
			if (!empty($username) && !empty($password)) {
				if(in_array(strtolower($username), $users)) {
					die("Username already taken, please choose another.");
				}
			if ($password !== $re_password) {
				die("Passwords don't match.");
			}
			$hash = password_hash($password, PASSWORD_DEFAULT);
			$stmt = $pdo->prepare('INSERT INTO users(user_name,user_pword) VALUES(?,?)');
			$stmt->bindParam(1, $username, PDO::PARAM_STR, 32);
			$stmt->bindParam(2, $hash, PDO::PARAM_STR, 512);
			$stmt->execute([$username, $hash]);
			echo "$username registered. <a href=\"log_in.php\">Proceed to login.</a>";
			} else {
				echo "Please enter a username and password.";
			}
		}
	} else {
		echo "You are already logged in. Please log out to register a new user.";
	}
	html_column_3();
	html_footer();

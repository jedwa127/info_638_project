<?php
	function html_head($page_title) {
		echo '
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
		<link rel="stylesheet" href="includes/style.css">
		<title>' . $page_title . '</title>
	</head>
			';
	}
	function html_header() {
		echo '
	<header class="p-3 bg-dark text-white">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-center">
			<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-start mb-md-0">
				<li><a href="index.php" class="nav-link px-2 text-white">Home</a></li>
				<li><a href="item_list.php" class="nav-link px-2 text-white">Browse Items</a></li>
				<li><a href="creator_list.php" class="nav-link px-2 text-white">Browse Creators</a></li>
				<li><a href="new_item.php" class="nav-link px-2 text-white">Upload an Item</a></li>
				<li><a href="new_creator.php" class="nav-link px-2 text-white">Add a Creator</a></li>
			</ul>
			<ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-right mb-md-0">
		';
		if (!isset($_SESSION['user_name'])) {
			echo '		
				<button type="button" class="btn btn-outline-light me-2" onclick="window.location.href=\'log_in.php\'">Log In</button>
				<button type="button" class="btn btn-outline-light me-2" onclick="window.location.href=\'new_user.php\'">Register</button>
			';
		} else {
			$un = htmlspecialchars($_SESSION['user_name']);
			echo '
				<div class="mx-2 my-2">Welcome, ' . $un . '</div>
				';
			echo '
				<button type="button" class="btn btn-outline-light me-2" onclick="window.location.href=\'log_out.php\'">Log Out</button>
				';
//			echo "<div class=\"me-2\">Welcome, $un.</div>";
		}
		echo '
				<form action="search.php" method="GET" class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
					<input type="search" id="search" name="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
				</form>
					<div class="text-end">
					</div>
				</ul>
			</div>
		</div>
	</header>
	<body>
			';
	}
	function html_footer() {
		echo '
	</body>
</html>
			';
	}
	function html_column_1() {
	echo '
		<div>
			<div class="row">
				<div class="col-2 mt-3">
		';
	}
	function html_column_2() {
	echo '
				</div>
				<div class="col-8 mt-3">
		';
	}
	function html_column_3() {
	echo '
				</div>
				<div class="col-2 mt-3">
				</div>
			</div>
		</div>
		';
	}
	function pdf_embed($file_path) {
		echo '
		<div class="">
			<br>
			<iframe src="' . $file_path . '" width="500" height="375" frameborder="0"></iframe>
		</div>
			';
	}
	function image_embed($file_path) {
		echo '
		<div class="">
			<br>
			<img src="' . $file_path . '" alt="">
		</div>
			';
	}
	function audio_embed($file_path) {
		echo '
		<div class="">
			<br>
			<audio controls>
				<source src="' . $file_path . '" type="audio/">
				Your browser does not support HTML audio.
			</audio>
		</div>
			';
	}
	function video_embed($file_path) {
		echo '
		<div class="">
			<br>
			<video width="1280" height="720" controls>
				<source src="' . $file_path . '" type="video/">
				Your browser does not support HTML video.
			</video> 
		</div>
			';
	}
	function destroy_session() {
		session_start();
		$_SESSION = array();
		setcookie(session_name(), '', time() - 2592000, '/');
		session_destroy();
	}
	function sanitize_string($str) {
		$str = htmlentities($str);
		$str = stripslashes($str);
		$str = strip_tags($str);
		return $str;
	}

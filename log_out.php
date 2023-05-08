<?php
	require_once 'includes/library.php';
	require_once 'includes/login.php';
	destroy_session();
	$previous = $_SERVER['HTTP_REFERER'];
	header("Location: $previous");
	die();

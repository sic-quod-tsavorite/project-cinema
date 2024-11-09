<?php
	session_start();
	ob_start();
	
	function logged_in() {
		return isset($_SESSION['user_id']);
	}
	
	function confirm_logged_in() {
		if (!logged_in()) {
			header("Location: $GLOBALS[base_url]");
	    	exit;
		}
	}

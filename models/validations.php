<?php 

	function validEmail($email) {
		// TODO: check email against valid emails from database
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function validPassword() {
		// TODO: check password against valid passwords from database
	}

	function validCourseID($courseID) {
		return ctype_alnum( (int) $courseID) && strlen($courseID) == 4;
	}

	function validQuarter($quarter) {
		$validQuarters = array("fall", "winter", "spring", "summer");

		return in_array($quarter, $validQuarters);
	}

	function validDate($year) {
		return $year >= date('Y');
	}

	function validGithubUrl($url) {
		if(filter_var($url, FILTER_VALIDATE_URL)) {
			if(parse_url($url, PHP_URL_HOST) == "github.com")
				return true;
		}

		return false;
	}

	function validTrelloUrl($url) {
		if(filter_var($url, FILTER_VALIDATE_URL)) {
			if(parse_url($url, PHP_URL_HOST) == "trello.com")
				return true;
		}

		return false;
	}
	
	function validUrl($url) {
		return filter_var($url, FILTER_VALIDATE_URL);
	}

	function validPhone($phone) {
		return preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}/", $phone) ||
				preg_match("/^[0-9]{10}/", $phone);
	}

	function validZip($zipcode) {
		return preg_match("/[0-9]{5}/", $zipcode);
	}
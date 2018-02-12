<?php

	function validCourseID($courseID) {
		return ctype_alnum( (int) $courseID) && strlen($courseID) == 4);
	}

	function validQuarter($quarter) {
		$validQuarters = array("fall", "winter", "spring", "summer");

		return in_array($quarter, $validQuarters);
	}

	function validYear($year) {
		return $year >= date('Y');
	}

	function validUrl($url) {
		return filter_var($url, FILTER_VALIDATE_URL);
	}
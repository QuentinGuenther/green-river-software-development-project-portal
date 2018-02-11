<?php

	function validCourseID($courseID) {
		return ctype_alnum( (int) $courseID) && strlen($courseID) == 4);
	}

	function validQuarter($quarter) {
		$validQuarters = array("fall", "winter", "spring", "summer");

		return is_array($quarter, $validQuarters);
	}

	function validYear($year) {
		return $year >= date('Y');
	}

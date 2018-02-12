<?php

	function validEmail($email) {
		// TODO: check email against valid emails from database
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function validPassword() {
		// TODO: check password against valid passwords from database
	}
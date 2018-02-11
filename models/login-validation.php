<?php

	function validEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	function validPassword() {
		// TODO: check password against valid passwords from database
	}
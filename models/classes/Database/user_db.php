<?php

    /**
     * This class provides functionallity for the users tabel.
     * @author Quentin Guenther
     * @copyright 2018
     */
    class UserDB extends RestDB
    {
        /**
         * instantiate a new UserDB object.
         */
        public function __construct()
        {
            parent::__construct();
        }

        /**
         * Check if user exists in the database
         *
         * @param string $username Users username.
         * @param string $password Users unencripted password.
         * @return boolean
         */
        public static function login($username, $password)
        {
            $sql = "SELECT userID, username, password, dateCreated, dateModified FROM Users WHERE username = :username";
            $params = array(':username' => array($username => PDO::PARAM_STR));

            $result = parent::get($sql, $params);
            $result = $result[0];

            return $result['password'] == sha1($password);
        }
    }
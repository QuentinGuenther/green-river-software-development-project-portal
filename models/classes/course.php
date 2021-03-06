<?php

	/**
	 * Class that represents a course
	 * 
	 * @author NathanCorbin
	 * @copyright 2018
	 * @version 1.0
	 */
	class Course
	{
		// course information
		private $_courseId;
		private $_courseNumber;
		private $_quarter;
		private $_year;
		private $_instructor;

		// project urls
		private $_github;
		private $_trello;
		private $_url;
		
		// login credentials for the project
		private $_username;
		private $_password;

		// notes
		private $_instructorNotes;

		// project
		private $_project;

		/**
		 * Creates a new class
		 * @param int $courseId 4 digit course id code
		 * @param string $quarter quarter for the class
		 * @param int $year the year the class will be taken
		 * @param string $instructor the instructors name
		 * @param string $github the github url
		 * @param string $trello the trello url
		 * @param string $url the project url
		 * @param string $username the username to login
		 * @param string $password the password to login
		 * @param string $instructorNotes the notes to set
		 * @param Project project the project that the class belongs to
		 */
		public function __construct($courseId,
									$courseNumber,
									$quarter, 
									$year, 
									$instructor,
									$github = null,
									$trello = null,
									$url = null,
									$username = null,
									$password = null,
									$instructorNotes = null,
									$project)
		{
			$this->courseId = $courseId;
			$this->courseNumber = $courseNumber;
			$this->quarter = $quarter;
			$this->year = $year;
			$this->instructor = $instructor;

			$this->github = $github;
			$this->trello = $trello;
			$this->url = $url;
			$this->username = $username;
			$this->password = $password;
			$this->instructorNotes = $instructorNotes;
			$this->project = $project;
		}

		/**
		 * Gets the courseId
		 * @return int
		 */
		public function getCourseId()
		{
			return $this->courseId;
		}

		/**
		 * Gets the courseNumber
		 * @return int
		 */
		public function getCourseNumber()
		{
			return $this->courseNumber;
		}

		/**
		 * Gets the quarter for the class
		 * @return string
		 */
		public function getQuarter()
		{
			return $this->quarter;
		}

		/**
		 * Gets the year for the class
		 * @return int
		 */
		public function getYear()
		{
			return $this->year;
		}

		/**
		 * Gets the instructor name
		 * @return string
		 */
		public function getInstructor()
		{
			return $this->instructor;
		}

		/**
		 * Gets the project github url
		 * @return string 
		 */
		public function getGithub()
		{
			return $this->github;
		}

		/**
		 * Gets the project trello url
		 * @return string 
		 */
		public function getTrello()
		{
			return $this->trello;
		}

		/**
		 * Gets the project url
		 * @return string 
		 */
		public function getUrl()
		{
			return $this->url;
		}

		/**
		 * Gets the the username credential 
		 * for the project website
		 * 
		 * @return string 
		 */
		public function getUsername()
		{
			return $this->username;
		}

		/**
		 * Gets the the password credential 
		 * for the project website
		 * 
		 * @return string 
		 */
		public function getPassword()
		{
			return $this->password;
		}

		/**
		 * Gets the instructors notes
		 * @return string
		 */
		public function getInstructorNotes()
		{
			return $this->instructorNotes;
		}

		/**
		 * Gets the Project
		 * @return string
		 */
		public function getProject()
		{
			return $this->project;
		}

		/**
		 * Sets the courseId for the class
		 * @param int $courseId new 4 digit course code
		 */
		public function setCourseId($courseId)
		{
			$this->courseId = $courseId;
		}

		/**
		 * Sets the quarter for the class
		 * @param string $quarter the quarter for the class
		 */
		public function setQuarter($quarter)
		{
			$this->quarter = $quarter;
		}

		/**
		 * Sets the year for the class
		 * @param int $year the year for the class
		 */
		public function setYear($year)
		{
			$this->year = $year;
		}

		/**
		 * Sets the instructor's name for the class
		 * @param string $instructor the instructors name
		 */
		public function setInstructor($instructor)
		{
			$this->instructor = $instructor;
		}

		/**
		 * Sets the github url
		 * @param string $github the github url
		 */
		public function setGithub($github)
		{
			$this->github = $github;
		}

		/**
		 * Sets the trello url
		 * @param string $trello the trello url
		 */
		public function setTrello($trello)
		{
			$this->trello = $trello;
		}

		/**
		 * Sets the project url
		 * @param string $url the project url
		 */
		public function setUrl($url)
		{
			$this->url = $url;
		}

		/**
		 * Sets the login username credential for the project
		 * @param string $username the username to login
		 */
		public function setUsername($username)
		{
			$this->username = $username;
		}

		/**
		 * Sets the login username credential for the project
		 * @param string $password the password to login
		 */
		public function setPassword($password)
		{
			$this->password = $password;
		}

		/**
		 * Sets the instructors notes
		 * @param string $instructorNotes the notes to set
		 */
		public function setInstructorNotes($instructorNotes)
		{
			$this->instructorNotes = $instructorNotes;
		}

		/**
		 * Sets the project
		 * @param Project project the project that the class belongs to
		 */
		public function setProject($project)
		{
			$this->project = unserialize($project);
		}
	}
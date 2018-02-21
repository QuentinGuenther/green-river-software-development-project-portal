<?php

	/**
	 * Class that represents a class (school)
	 * 
	 * @author NathanCorbin
	 * @copyright 2018
	 * @version 1.0
	 */
	class Class extends Project
	{
		private $_courseId;
		private $_quarter;
		private $_year;
		private $_instructor;

		/**
		 * Creates a new class
		 * @param int $courseId 4 digit course id code
		 * @param string $quarter quarter for the class
		 * @param int $year the year the class will be taken
		 * @param string $instructor the instructors name
		 */
		public function __construct($courseId, $quarter, $year, $instructor)
		{
			$this->courseId = $courseId;
			$this->quarter = $quarter;
			$this->year = $year;
			$this->instructor;
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
	}
<?php

	/**
	 * The project class represents a project on the Green River Software 
	 * Development Portal.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */
	class Project 
	{
		private $_projectTitle;
		private $_description;
		private $_status; // Values: "pending", "active", "maintenance", "retired"



		/**
		 * Get the title of the project.
		 * @return string The title of the project.
		 */
		public function getProjectTitle()
		{
			return $this->projectTitle;
		}

		/** 
		 * Set the title of the project.
		 * @param string $projectTitle The title of the project.
		 * @return null
		 */
		public function setProjectTitle($projectTitle)
		{
			$this->projectTitle = $projectTitle;
		}

		/**
		 * Get the description of the project.
		 * @return string The description of the project.
		 */
		public function getDescription()
		{
			return $this->description;
		}

		/** 
		 * Set the description of the project.
		 * @param string $description The description of the project.
		 * @return null
		 */
		public function setDescription($description)
		{
			$this->description = $description;
		}

		/**
		 * Get the status of the project.
		 * @return string The status of the project.
		 */
		public function getStatus()
		{
			return $this->status;
		}

		/** 
		 * Set the status of the project.
		 * @param string $status The status of the project.
		 * @return null
		 */
		public function setStatus($status)
		{
			$this->status = $status;
		}

	}
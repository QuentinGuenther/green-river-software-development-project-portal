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
		private $_client;

		/**
		 * Instantiate a new project.
		 * @param string $projectTitle The title of the project.
		 * @param string $description The description of the project.
		 * @param string $status The status of the project.
		 * @param Client $client an instance of a Client to the project.
		 * @return null
		 */
		public function __construct($projectTitle, $description, $status, $client)
		{
			$this->projectTitle = $projectTitle;
			$this->description = $description;
			$this->status = $status;
			$this->client = $client;
		}

		/**
		 * Get the title of the project.
		 * @return string The title of the project;
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
		 * @return string The description of the project;
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
		public function setDescritpion($description)
		{
			$this->description = $description;
		}

		/**
		 * Get the status of the project.
		 * @return string The status of the project;
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

		/**
		 * Get the client for the project.
		 * @return Client The client for the project;
		 */
		public function getClient()
		{
			return $this->client;
		}

		/** 
		 * Set the client for the project.
		 * @param Client $client The client for the project.
		 * @return null
		 */
		public function setClient($client)
		{
			$this->client = $client;
		}
	}
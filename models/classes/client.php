<?php

	/**
	 * The client class represents a client for a project on the
	 * Green River Software Development Portal.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */
	class Client extends ClientCompany
	{
		private $_clientName;
		private $_jobTitle;
		private $_email;
		private $_phoneNumber;

		/**
		 * Instantiate a new client.
		 * @param string $clientName The client's name.
		 * @param string $jobTitle The client's job title.
		 * @param string $email The client's contact email.
		 * @param string $phoneNumber The client's contact phone number.
		 * @param string $companyName The name of the company.
		 * @param string $website The company's website.
		 * @param string $streetAddress The company's address.
		 * @param string $state The company's 2 letter state abbreviation. 
		 * @param string $city The company's city.
		 * @param string $postalCode The company's 5 didget zipcode.
		 * @return null
		 */
		public function __construct($clientName,
									$jobTitle,
									$email,
									$phoneNumber,
									$companyName,
									$website,
									$streetAddress,
									$state,
									$city,
									$postalCode)
		{
			parent::__construct($companyName,
								$website,
								$streetAddress,
								$state,
								$city,
								$postalCode);
			$this->clientName = $clientName;
			$this->jobTitle = $jobTitle;
			$this->email = $email;
			$this->phoneNumber = $phoneNumber;
		}

		/**
		 * Get the client's name.
		 * @return string The client's name.
		 */
		public function getClientName()
		{
			return $this->clientName;
		}

		/**
		 * Set the name of the client.
		 * @param string $clientName The client's name.
		 * @return null
		 */
		public function setClientName($clientName)
		{
			$this->clientName = $clientName;
		}

		/**
		 * Get the client's job title.
		 * @return string The client's job title.
		 */
		public function getJobTitle()
		{
			return $this->jobTitle;
		}

		/**
		 * Set the job title of the client.
		 * @param string $jobTitle The client's job title.
		 * @return null
		 */
		public function setJobTitle($jobTitle)
		{
			$this->jobTitle = $jobTitle;
		}

		/**
		 * Get the client's email.
		 * @return string The client's email.
		 */
		public function getEmail()
		{
			return $this->email;
		}

		/**
		 * Set the email of the client.
		 * @param string $email The client's email.
		 * @return null
		 */
		public function setEmail($email)
		{
			$this->email = $email;
		}

		/**
		 * Get the client's phone number.
		 * @return string The client's phone number.
		 */
		public function getPhoneNumber()
		{
			return $this->phoneNumber;
		}

		/**
		 * Get the client's website number.
		 * @return string The client's website.
		 */
		public function getWebsite()
		{
			return $this->website;
		}


		/**
		 * Set the phone number of the client.
		 * @param string $phoneNumber The client's phone number.
		 * @return null
		 */
		public function setPhoneNumber($phoneNumber)
		{
			$this->phoneNumber = $phoneNumber;
		}
	}
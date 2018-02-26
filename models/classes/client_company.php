<?php

	/**
	 * The ClientCompany class represents a client's company.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */
	class ClientCompany
	{
		private $_companyName;
		private $_website;
		private $_streetAddress;
		private $_state;
		private $_city;
		private $_postalCode;

		/**
		 * Instantiate a new company
		 * @param string $companyName The name of the company.
		 * @param string $website The company's website.
		 * @param string $streetAddress The company's address.
		 * @param string $state The company's 2 letter state abbreviation. 
		 * @param string $city The company's city.
		 * @param string $postalCode The company's 5 didget zipcode.
		 */
		public function __construct($companyName,
									$website,
									$streetAddress,
									$state,
									$city,
									$postalCode)
		{
			$this->companyName = $companyName;
			$this->website = $website;
			$this->streetAddress = $streetAddress;
			$this->state = $state;
			$this->city = $city;
			$this->postalCode = $postalCode;
		}

		/**
		 * Get the name of the company.
		 * @return string The name of the company.
		 */
		public function getCompanyName()
		{
			return $this->companyName;
		}

		/** 
		 * Set the name of the company.
		 * @param string $companyName The name of the company.
		 * @return null
		 */
		public function setCompanyName($companyName)
		{
			$this->companyName = $companyName;
		}

		/**
		 * Get the website of the company.
		 * @return string The company's website.
		 */
		public function getWebsite()
		{
			return $this->website;
		}

		/** 
		 * Set the website of the company.
		 * @param string $website The website of the company.
		 * @return null
		 */
		public function setWebiste($website)
		{
			$this->website = $website;
		}

		/**
		 * Get the address of the company.
		 * @return string The company's address.
		 */
		public function getStreetAddress()
		{
			return $this->streetAddress;
		}

		/** 
		 * Set the address of the company.
		 * @param string $streetAddress The company's address.
		 * @return null
		 */
		public function setStreetAddress($streetAddress)
		{
			$this->streetAddress = $streetAddress;
		}

		/**
		 * Get the company's 2 letter state abbreviation.
		 * @return string The company's 2 letter state abbreviation. 
		 */
		public function getState()
		{
			return $this->state;
		}

		/** 
		 * Set the company's 2 letter state abbreviation.
		 * @param string $state The company's 2 letter state abbreviation.
		 * @return null
		 */
		public function setState($state)
		{
			$this->state = $state;
		}

		/**
		 * Get the company's city.
		 * @return string The company's city.
		 */
		public function getCity()
		{
			return $this->city;
		}

		/** 
		 * Set the company's city.
		 * @param string $city The company's city.
		 * @return null
		 */
		public function setCity($city)
		{
			$this->city = $city;
		}

		/**
		 * Get the zip code of the company.
		 * @return string The company's 5 didget zipcode.
		 */
		public function getPostalCode()
		{
			return $this->postalCode;
		}

		/** 
		 * Set the zip code of the company.
		 * @param string $postalCode The company's 5 didget zipcode.
		 * @return null
		 */
		public function setPostalCode($postalCode)
		{
			$this->postalCode = $postalCode; 
		}
	}
<?php
	
	/**
	 * This class provides functionallity to work with the Company table in the database.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */
	class CompanyDB extends RestDB
	{
		/**
		 * Instantiate a new CompanyDB.
		 * @return null.
		 */
		public function __construct()
		{
			parent::__construct();
		}

		/**
		 * Get a company with ID.
		 * @param int $id company ID.
		 * @return New ClientCompany object.
		 */
		public static function getCompany($id)
		{
			$sql = "SELECT name, website, address, city, state, zipCode FROM Company WHERE companyID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);

			$result = $result[0];

			$company = new ClientCompany($result['name'],
										$result['website'],
										$result['address'],
										$result['state'],
										$result['city'],
										$result['zipCode']);

			return $company;
		}

		/**
		 * This function inserts a new company into the database,
		 * unless an identical row exists.
		 * @param ClientCompany $company A company object.
		 * @return ID of company.
		 */
		public static function insertCompany($company)
		{			
			$match = self::matchToRow($company);
			if(!empty($match))
				return $match[0]['companyID'];

			$sql = "INSERT INTO Company(name, website, address, city, state, zipCode) VALUES (:name, :website, :address, :city, :state, :zipCode)";
			
			$params = array(
				':name' => array($company->getCompanyName() => PDO::PARAM_STR),
				':website' => array($company->getWebsite() => PDO::PARAM_STR),
				':address' => array($company->getStreetAddress() => PDO::PARAM_STR),
				':city' => array($company->getCity() => PDO::PARAM_STR),
				':state' => array($company->getState() => PDO::PARAM_STR),
				':zipCode' => array($company->getPostalCode() => PDO::PARAM_STR)
			);

			return parent::insert($sql, $params);
		}

		/**
		 * This function updates a company.
		 * @param ClientCompany $company The data to replace the old with.
		 * @param int $id The ID of the company.
		 * @return boolean Success.
		 */
		public static function updateCompany($company, $id)
		{
			$sql = "UPDATE Company SET name = :name, website = :website, address = :address, city = :city, state = :state, zipCode = :zipCode WHERE companyID = :id";

			$params = array(
				':name' => array($company->getCompanyName() => PDO::PARAM_STR),
				':website' => array($company->getWebsite() => PDO::PARAM_STR),
				':address' => array($company->getStreetAddress() => PDO::PARAM_STR),
				':city' => array($company->getCity() => PDO::PARAM_STR),
				':state' => array($company->getState() => PDO::PARAM_STR),
				':zipCode' => array($company->getPostalCode() => PDO::PARAM_STR),
				':id' => array($id => PDO::PARAM_INT)
			);

			return parent::update($sql, $params);
		}

		/**
		 * This function deletes a company from the database.
		 * @param int @id The ID of the company to delete.
		 * @return boolean Success.
		 */
		public static function deleteCompany($id)
		{
			$sql = "DELETE FROM Company WHERE companyID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));

			return parent::delete($sql, $params);
		}

		/**
		 * This function checks if there is a row in the database with the same information as the company
		 * @param ClientCompany $company
		 * @return ID of the matching row(s).
		 */ 
		protected static function matchToRow($company)
		{
			$sql = "SELECT companyID FROM Company WHERE name = :name AND website = :website AND address = :address AND city = :city AND state = :state AND zipCode = :zipCode";
			$params = array(
				':name' => array($company->getCompanyName() => PDO::PARAM_STR),
				':website' => array($company->getWebsite() => PDO::PARAM_STR),
				':address' => array($company->getStreetAddress() => PDO::PARAM_STR),
				':city' => array($company->getCity() => PDO::PARAM_STR),
				':state' => array($company->getState() => PDO::PARAM_STR),
				':zipCode' => array($company->getPostalCode() => PDO::PARAM_STR)
			);

			return parent::get($sql, $params);
		}
	}
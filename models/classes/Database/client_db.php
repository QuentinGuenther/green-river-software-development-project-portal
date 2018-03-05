<?php
	
	/**
	 * This class provides functionallity to work with the Client table in the database.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */
	class ClientDB extends RestDB
	{
		/**
		 * Instantiate a new ClientDB.
		 * @return null.
		 */
		public function __construct()
		{
			parent::__construct();
		}

		/**
		 * Get a client and company with ID.
		 * @param int $id client ID.
		 * @return New Client object.
		 */
		public static function getClient($id)
		{
			new CompanyDB();

			$sql = "SELECT name, jobTitle, email, phoneNumber, companyID FROM Client WHERE clientID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);
			$result = $result[0];

			$company = CompanyDB::getCompany($result['companyID']);

			$client = new Client($result['name'],
								$result['jobTitle'],
								$result['email'],
								$result['phoneNumber'],
								$company->getCompanyName(),
								$company->getWebsite(),
								$company->getStreetAddress(),
								$company->getState(),
								$company->getCity(),
								$company->getPostalCode());

			return $client;
		}

		/**
		 * This function inserts a new client into the database,
		 * unless an identical row exists. Also inserts the client's
		 * company.
		 * @param Client $client A client object.
		 * @return ID of client.
		 */
		public static function insertClient($client)
		{	
			new CompanyDB();
			$company = new ClientCompany($client->getCompanyName(),
									$client->getWebsite(),
									$client->getStreetAddress(),
									$client->getState(),
									$client->getCity(),
									$client->getPostalCode());
			$companyID = CompanyDB::insertCompany($company);

			$match = self::matchToRow($client);
			if(!empty($match))
				return $match[0]['companyID'];

			$sql = "INSERT INTO Client(name, jobTitle, email, phoneNumber, companyID) VALUES (:name, :jobTitle, :email, :phoneNumber, :companyID)";
			
			$params = array(
				':name' => array($client->getClientName() => PDO::PARAM_STR),
				':jobTitle' => array($client->getJobTitle() => PDO::PARAM_STR),
				':email' => array($client->getEmail() => PDO::PARAM_STR),
				':phoneNumber' => array($client->getPhoneNumber() => PDO::PARAM_STR),
				':companyID' => array($companyID => PDO::PARAM_INT)
			);		

			return parent::insert($sql, $params);
		}

		/**
		 * This function updates a client.
		 * @param Client $client The data to replace the old with.
		 * @param int $id The ID of the client.
		 * @return boolean Success.
		 */
		public static function updateClient($client, $id)
		{
			new CompanyDB();
			$company = new ClientCompany($client->getCompanyName(),
									$client->getWebsite(),
									$client->getStreetAddress(),
									$client->getState(),
									$client->getCity(),
									$client->getPostalCode());
			CompanyDB::updateCompany($company, self::getCompanyID($id));

			$sql = "UPDATE Client SET name = :name, jobTitle = :jobTitle, email = :email, phoneNumber = :phoneNumber WHERE clientID = :id";

			$params = array(
				':name' => array($client->getClientName() => PDO::PARAM_STR),
				':jobTitle' => array($client->getJobTitle() => PDO::PARAM_STR),
				':email' => array($client->getEmail() => PDO::PARAM_STR),
				':phoneNumber' => array($client->getPhoneNumber() => PDO::PARAM_STR),
				':id' => array($id => PDO::PARAM_INT)
			);

			return parent::update($sql, $params);
		}

		/**
		 * This function deletes a client from the database.
		 * Also deletes the client's company if no other client
		 * belongs to the same company.
		 * @param int @id The ID of the client to delete.
		 * @return boolean Success.
		 */
		public static function deleteClient($id)
		{
			$companyID = self::getCompanyID($id);

			$sql = "DELETE FROM Client WHERE clientID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$success = parent::delete($sql, $params);

			if(!self::isCompany($companyID)){
				new CompanyDB();
				CompanyDB::deleteCompany($companyID);
			}

			return $success;
		}

		/**
		 * This function checks if there is a row in the database with the same information as the client
		 * @param Client $client
		 * @return ID of the matching row(s).
		 */ 
		public static function matchToRow($client)
		{
			$sql = "SELECT clientID FROM Client WHERE name = :name AND jobTitle = :jobTitle AND email = :email AND phoneNumber = :phoneNumber";
			$params = array(
				':name' => array($client->getClientName() => PDO::PARAM_STR),
				':jobTitle' => array($client->getJobTitle() => PDO::PARAM_STR),
				':email' => array($client->getEmail() => PDO::PARAM_STR),
				':phoneNumber' => array($client->getPhoneNumber() => PDO::PARAM_STR)
			);

			return parent::get($sql, $params);
		}

		/**
		 * Get the client's company ID.
		 * @param int @id The ID of the client.
		 * @return int The companys ID.
		 */
		private static function getCompanyID($id)
		{
			$sql = "SELECT companyID FROM Client WHERE clientID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);
			$result = $result[0];

			return $result['companyID'];
		}

		/**
		 * Checks if any clients have the company id.
		 * @param int @id Comapny forign key.
		 * @return boolean Does exist.
		 */
		private static function isCompany($id)
		{
			$sql = "SELECT clientID FROM Client WHERE companyID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);

			return !empty($result[0]);
		}
	}
<?php
	
	/**
	 * This class provides functionallity to work with the Project table in the database, along with automatically 
	 * updating the Client and company tables as needed.
	 * @author Quentin Guenther
	 * @copyright 2018
	 */
	class ProjectDB extends RestDB
	{
		/**
		 * Instantiate a new ProjectDB.
		 * @return null.
		 */
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * Get all projects from database.
		 * @return Associative array of projects.
		 */
		public static function getAllProjects() {
			$sql = "SELECT * FROM Project";
			return parent::get($sql);
		}

		/**
		 * Get a project with ID.
		 * @param int $id project ID.
		 * @return New Project object.
		 */
		public static function getProject($id)
		{
			new ClientDB();
			$sql = "SELECT projectID, title, description, status, clientID FROM Project WHERE projectID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);
			$result = $result[0];

			$client = ClientDB::getClient($result['clientID']);

			$project = new Project($result['title'], $result['description'], $result['status'], $client);

			return $project;
		}
		
		/**
		 * This function inserts a new project into the database,
		 * unless an identical row exists. Also inserts the project's
		 * client.
		 * @param Project $project A project object.
		 * @return ID of project.
		 */
		public static function insertProject($project)
		{
			new ClientDB();
			$clientID = ClientDB::insertClient($project->getClient());
			
			$match = self::matchToRow($project);
			if(!empty($match))
				return $match[0]['projectID'];
			

			$sql = "INSERT INTO Project(title, description, status, clientID) VALUES (:title, :description, :status, :clientID)";
			
			$params = array(
				':title' => array($project->getProjectTitle() => PDO::PARAM_STR),
				':description' => array($project->getDescription() => PDO::PARAM_STR),
				':status' => array($project->getStatus() => PDO::PARAM_STR),
				':clientID' => array($clientID => PDO::PARAM_INT)
			);		

			return parent::insert($sql, $params);
		}
		
		/**
		 * This function updates a project.
		 * @param Project $project The data to replace the old with.
		 * @param int $id The ID of the project.
		 * @return boolean Success.
		 */
		public static function updateProject($project, $id)
		{
			new ClientDB();
			
			ClientDB::updateClient($project->getClient(), self::getClientID($id));

			$sql = "UPDATE Project SET title = :title, description = :description, status = :status WHERE projectID = :id";

			$params = array(
				':title' => array($project->getProjectTitle() => PDO::PARAM_STR),
				':description' => array($project->getDescription() => PDO::PARAM_STR),
				':status' => array($project->getStatus() => PDO::PARAM_STR),
				':id' => array($id => PDO::PARAM_INT)
			);

			return parent::update($sql, $params);
		}
		
		/**
		 * This function deletes a project from the database.
		 * Also deletes the project's client if no other project
		 * belongs to the same client.
		 * @param int @id The ID of the project to delete.
		 * @return boolean Success.
		 */
		public static function deleteProject($id)
		{
			$clientID = self::getClientID($id);

			$sql = "DELETE FROM Project WHERE projectID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$success = parent::delete($sql, $params);

			if(!self::isClient($clientID)){
				new ClientDB();
				ClientDB::deleteClient($clientID);
			}

			return $success;
		}
		

		/**
		 * Get the project's client ID.
		 * @param int @id The ID of the project.
		 * @return int The client's ID.
		 */
		private static function getClientID($id)
		{
			$sql = "SELECT clientID FROM Project WHERE projectID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);
			$result = $result[0];

			return $result['clientID'];
		}

		/**
		 * Checks if any projects have the client id.
		 * @param int @id Client forign key.
		 * @return boolean Does exist.
		 */
		private static function isClient($id)
		{
			$sql = "SELECT projectID FROM Project WHERE clientID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);

			return !empty($result[0]);
		}

		/**
		 * This function checks if there is a row in the database with the same information as the project
		 * @param Project $project
		 * @return ID of the matching row(s).
		 */ 
		protected static function matchToRow($project)
		{
			//new ClientDB();
			//$clientID = ClientDB::matchToRow($project->getClient());

			$sql = "SELECT projectID FROM Project WHERE title = :title AND description = :description AND status = :status";
			$params = array(
				':title' => array($project->getProjectTitle() => PDO::PARAM_STR),
				':description' => array($project->getDescription() => PDO::PARAM_STR),
				':status' => array($project->getStatus() => PDO::PARAM_STR)
				//':clientID' => array($clientID => PDO::PARAM_INT)
			);

			return parent::get($sql, $params);
		}

	}
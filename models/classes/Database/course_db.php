<?php

	class CourseDB extends RestDB
	{
		/**
		 * Instantiate a new CourseDB.
		 * @return null.
		 */
		public function __construct()
		{
			parent::__construct();
		}

		/**
		 * Get a course with ID.
		 * @param int $id Course ID.
		 * @return New Course object.
		 */
		public static function getCourse($id) 
		{
			new ProjectDB();
			$sql = "SELECT courseNumber, quarter, year, instructor, github, trello, url, username, password, notes, projectID FROM Course WHERE courseID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);
			$result = $result[0];

			$project = ProjectDB::getProject($result['projectID']);

			$course = new Course($result['courseNumber'], 
								$result['quarter'], 
								$result['year'], 
								$result['instructor'],
								$result['github'],
								$result['trello'],
								$result['url'],
								$result['username'],
								$result['password'],
								$result['notes'],
								$project);

			return $course;
		}

		/**
		 * This function inserts a new course into the database,
		 * unless an identical row exists. Also inserts the course's
		 * project.
		 * @param Course $course A course object.
		 * @return ID of course.
		 */
		public static function insertCourse($course)
		{
			new ProjectDB();
			$projectID = ProjectDB::insertProject($course->getProject());

			$match = self::matchToRow($course);
			if(!empty($match))
				return $match[0]['courseID'];

			$sql = "INSERT INTO Course(courseNumber, projectID, quarter, year, instructor, github, trello, url, username, password, notes) VALUES (:courseNumber, :projectID, :quarter, :year, :instructor, :github, :trello, :url, :username, :password, :notes)";
			$params = array(
				':courseNumber' => array($course->getCourseId() => PDO::PARAM_STR),
				':projectID' => array($projectID => PDO::PARAM_INT),
				':quarter' => array($course->getQuarter() => PDO::PARAM_STR),
				':year' => array($course->getYear() => PDO::PARAM_STR),
				':instructor' => array($course->getInstructor() => PDO::PARAM_STR),
				':github' => array($course->getGithub() => PDO::PARAM_STR),
				':trello' => array($course->getTrello() => PDO::PARAM_STR),
				':url' => array($course->getUrl() => PDO::PARAM_STR),
				':username' => array($course->getUsername() => PDO::PARAM_STR),
				':password' => array($course->getPassword() => PDO::PARAM_STR),
				':notes' => array($course->getInstructorNotes() => PDO::PARAM_STR)
			);

			return parent::insert($sql, $params);
		}

		/**
		 * This function updates a course.
		 * @param Course $course The data to replace the old with.
		 * @param int $id The ID of the course.
		 * @return boolean Success.
		 */
		public static function updateCourse($course, $id)
		{
			new ProjectDB();
			
			ProjectDB::updateProject($course->getProject(), self::getProjectID($id));

			$sql = "UPDATE Course SET courseNumber = :courseNumber, quarter = :quarter, year = :year, instructor = :instructor, github = :github, trello = :trello, url = :url,  username = :username, password = :password, notes = :notes WHERE courseID = :id";

			$params = array(
				':courseNumber' => array($course->getCourseId() => PDO::PARAM_STR),
				':quarter' => array($course->getQuarter() => PDO::PARAM_STR),
				':year' => array($course->getYear() => PDO::PARAM_STR),
				':instructor' => array($course->getInstructor() => PDO::PARAM_STR),
				':github' => array($course->getGithub() => PDO::PARAM_STR),
				':trello' => array($course->getTrello() => PDO::PARAM_STR),
				':url' => array($course->getUrl() => PDO::PARAM_STR),
				':username' => array($course->getUsername() => PDO::PARAM_STR),
				':password' => array($course->getPassword() => PDO::PARAM_STR),
				':notes' => array($course->getInstructorNotes() => PDO::PARAM_STR),
				':id' => array($id => PDO::PARAM_INT)
			);

			return parent::update($sql, $params);
		}

		/**
		 * This function deletes a course from the database.
		 * @param int @id The ID of the course to delete.
		 * @return boolean Success.
		 */
		public static function deleteCourse($id)
		{
			$sql = "DELETE FROM Course WHERE courseID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));

			return parent::delete($sql, $params);
		}

		/**
		 * This function checks if there is a row in the database with the same information as the course
		 * @param Course $course
		 * @return ID of the matching row(s).
		 */ 
		protected static function matchToRow($course)
		{
			$sql = "SELECT courseID FROM Course WHERE courseNumber = :courseNumber AND quarter = :quarter AND year = :year AND instructor = :instructor AND github = :github AND trello = :trello AND url = :url AND username = :username AND password = :password AND notes = :notes";
			$params = array(
				':courseNumber' => array($course->getCourseId() => PDO::PARAM_STR),
				':quarter' => array($course->getQuarter() => PDO::PARAM_STR),
				':year' => array($course->getYear() => PDO::PARAM_STR),
				':instructor' => array($course->getInstructor() => PDO::PARAM_STR),
				':github' => array($course->getGithub() => PDO::PARAM_STR),
				':trello' => array($course->getTrello() => PDO::PARAM_STR),
				':url' => array($course->getUrl() => PDO::PARAM_STR),
				':username' => array($course->getUsername() => PDO::PARAM_STR),
				':password' => array($course->getPassword() => PDO::PARAM_STR),
				':notes' => array($course->getInstructorNotes() => PDO::PARAM_STR)
			);

			return parent::get($sql, $params);
		}

		/**
		 * Get the course's project ID.
		 * @param int @id The ID of the course.
		 * @return int The project's ID.
		 */
		private static function getProjectID($id)
		{
			$sql = "SELECT projectID FROM Course WHERE courseID = :id";
			$params = array(':id' => array($id => PDO::PARAM_INT));
			$result = parent::get($sql, $params);
			$result = $result[0];

			return $result['projectID'];
		}
	}
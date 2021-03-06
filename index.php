<?php
    session_start();

    // Turn on error reporting
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Require autoload
    require_once('vendor/autoload.php');

    // Require validations
    require_once('models/validations.php');

    // Create fat-free instance
    $f3 = Base::instance();

    // Set debug level to dev
    $f3->set('DEBUG', 3);

    /**
     * Default route - the homepage
     * 
     * @route GET|POST 
     * @alias /views/home.html
     * @param $f3 Base
     */
    $f3->route('GET|POST /', function($f3) {

        //$_SESSION['username'] = 'temp';

        if(!isset($_SESSION['username']))
            $f3->reroute('login');

        $template = new Template();

        $db = new ProjectDB();
        $projects = $db->getAllProjects();

        $f3->set('projects', $projects);

        echo $template->render('views/home.html');
    });

    /**
     * Route for the login page
     * 
     * @route GET|POST 
     * @alias /views/login.html
     * @param $f3 Base
     */
    $f3->route('GET|POST /login', function($f3) {

        if(isset($_POST['submit'])) {
            $errors = array();

            if(!validEmail($_POST['username']))
                $errors['username'] = 'Username must be a valid email';

            new UserDB();
            $username = $_POST['username'];
            $password = $_POST['password'];

            if(!UserDB::login($username, $password))
                $errors['invalidLogin'] = 'The username or password is invalid';

            $f3->set('username', $_POST['username']);
            $f3->set('errors', $errors);


            if(empty($errors)) {
                $_SESSION['username'] = $_POST['username'];
                $f3->reroute('/');
            }
        }

        echo Template::instance()->render('views/login.html');
    });

    /**
     * Route for the logout page
     * unsets the user from the session
     * 
     * @route GET 
     * @alias /views/logout.html
     * @param $f3 Base
     */
    $f3->route('GET /logout', function($f3) {
        if(isset($_SESSION['username']))
            unset($_SESSION['username']);


        $f3->reroute('login');
    });

    /**
     * Route for a new class
     * Takes a project id to 
     * create a new course for
     * a project

     * @route GET|POST 
     * @alias /views/forms/class.html
     * @param $f3 Base
     * @param $params array
     */
    $f3->route('GET|POST /new-class/@id', function($f3, $params) {
        if(!isset($_SESSION['username']))
            $f3->reroute('login');

        $quarters = array("fall", "winter", "spring", "summer");
        $f3->set("quarters", $quarters);

        new CourseDB();
        $course = CourseDB::getCourse($params['id']);

        $update = false; // update flag to see if we need to update course

        // if course has a number, set all the variables
        // to the page, also set the update flag to true
        if(!empty($course->getCourseNumber())) {
            $update = true;
            $f3->set('courseID', $course->getCourseNumber());
            $f3->set('quarter', $course->getQuarter());
            $f3->set('year', $course->getYear());
            $f3->set('instructor', $course->getInstructor());
            $f3->set('github', $course->getGithub());
            $f3->set('trello', $course->getTrello());
            $f3->set('url', $course->getUrl());
            $f3->set('username', $course->getUsername());
            $f3->set('password', $course->getPassword());
            $f3->set('notes', $course->getInstructorNotes());
        }

        if(isset($_POST['submit'])) {

            $errors = array();

            if(!validCourseId($_POST['courseID']))
                $errors['courseID'] = 'Course ID (ex., IT 305)';

            if(!validQuarter($_POST['quarter']))
                $errors['quarter'] = 'Invalid course quarter';

            if(!validDate($_POST['year']))
                $errors['year'] = "Year must be ".(date('Y') - 1)." or greater";

            if(!validGithubUrl($_POST['github']))
                $errors['github'] = "Not a github url";

            if(!validTrelloUrl($_POST['trello']))
                $errors['trello'] = "Not a trello url";

            if(!validUrl($_POST['url']))
                $errors['url'] = "Not a valid url";

            if(empty($_POST['instructor']))
                $errors['instructor'] = "Please enter an instructor for this class";

            $f3->set('courseID', $_POST['courseID']);
            $f3->set('quarter', $_POST['quarter']);
            $f3->set('year', $_POST['year']);
            $f3->set('github', $_POST['github']);
            $f3->set('trello', $_POST['trello']);
            $f3->set('url', $_POST['url']);
            $f3->set('instructor', $_POST['instructor']);
            $f3->set('username', $_POST['username']);
            $f3->set('password', $_POST['password']);
            $f3->set('notes', $_POST['notes']);
            $f3->set('errors', $errors);

            if(empty($errors)) {

                new ProjectDB();
                new CourseDB();

                $projectID = $params['id'];

                $project = ProjectDB::getProject($projectID);
                $course->setProject(serialize($project));

                $course = new Course($param['id'],
                                    $_POST['courseID'],
                                    $_POST['quarter'],
                                    $_POST['year'],
                                    $_POST['instructor'],
                                    $_POST['github'],
                                    $_POST['trello'],
                                    $_POST['url'],
                                    $_POST['username'],
                                    $_POST['password'],
                                    $_POST['notes'],
                                    $project);

                // insert course if update flag is not set
                if(!$update)
                {
                    $courseID = CourseDB::insertCourse($course);
                    
                    //$_SESSION['course'] = $course;

                    $f3->reroute('/course-summary/'.$courseID);
                }
                   
               
                else {
                    // update current course
                    CourseDB::updateCourse($course, $params['id']);
                    $f3->reroute('/course-summary/@id');
                }
                
                $f3->set('course', $course);
            }
        }

        echo Template::instance()->render('views/forms/class.html');
    });

    /**
     * Route for a new project
     * 
     * @route GET|POST 
     * @alias /views/forms/project_info.html
     * @param $f3 Base
     */
    $f3->route('GET|POST /new-project', function($f3) {
        if(!isset($_SESSION['username']))
            $f3->reroute('login');

        require('models/address-helpers.php');
        require('models/status-helpers.php');

        $f3->set('states', $states);
        $f3->set('status', $status);

        // check if id is set
        // if so, get the data from the
        // database using the id and set
        // the forms
        if(isset($_GET['id'])) {
            new ClientDB();
            new ProjectDB();

            $project = ProjectDB::getProject($_GET['id']);
            $client = $project->getClient();

            $f3->set('title', $project->getProjectTitle());
            $f3->set('description', $project->getDescription());
            $f3->set('projectStatus', $project->getStatus());
            $f3->set('company', $client->getCompanyName());
            $f3->set('website', $client->getWebsite());
            $f3->set('address', $client->getStreetAddress());
            $f3->set('zipcode', $client->getPostalCode());
            $f3->set('city', $client->getCity());
            $f3->set('clientName', $client->getClientName());
            $f3->set('clientJobTitle', $client->getJobTitle());
            $f3->set('clientEmail', $client->getEmail());
            $f3->set('clientPhone', $client->getPhoneNumber());
        }

        if(isset($_POST['submit'])) {
            $_POST['clientPhoneNumber'] = preg_replace("/[^0-9,.]/", "", $_POST['clientPhoneNumber']);

            $errors = array();

            if(empty($_POST['projectTitle']))
                $errors['title'] = 'Please enter a project title';

            $f3->set('title', $_POST['projectTitle']);
            $f3->set('description', $_POST['projectDescription']);
            $f3->set('projectStatus', $_POST['status']);
            $f3->set('company', $_POST['companyName']);
            $f3->set('website', $_POST['companyWebsite']);
            $f3->set('address', $_POST['address']);
            $f3->set('zipcode', $_POST['zipcode']);
            $f3->set('city', $_POST['city']);
            $f3->set('clientName', $_POST['clientName']);
            $f3->set('clientJobTitle', $_POST['clientJobTitle']);
            $f3->set('clientEmail', $_POST['clientEmail']);
            $f3->set('clientPhone', $_POST['clientPhoneNumber']);

            $f3->set('errors', $errors);

            if(empty($errors)) {

                $client = new Client($_POST['clientName'],
                                    $_POST['clientJobTitle'],
                                    $_POST['clientEmail'],
                                    $_POST['clientPhoneNumber'],
                                    $_POST['companyName'],
                                    $_POST['companyWebsite'],
                                    $_POST['address'],
                                    $_POST['state'][0],
                                    $_POST['city'],
                                    $_POST['zipcode']);

                $project = new Project($_POST['projectTitle'],
                                        $_POST['projectDescription'],
                                        $_POST['status'],
                                        $client);

                new ProjectDB();

                // if id is not set, insert new
                // row, otherwise just update the row
                if(!isset($_GET['id'])) {
                    $projectID = ProjectDB::insertProject($project);
                    $f3->reroute("/project-summary/$projectID");
                }
                
                else {
                    $projectID = ProjectDB::updateProject($project, $_GET['id']);
                    $f3->reroute('/project-summary/'.$_GET['id']);
                }
            }
        }

        echo Template::instance()->render('views/forms/project_info.html');
    });

    /**
     * Route for a project summary page
     * Takes an id to search from the database
     * with
     * 
     * @route GET|POST 
     * @alias /views/summary_pages/project_summary.html
     * @param $f3 Base
     * @param $params array
     */
    $f3->route('GET /project-summary/@id', function($f3, $params) {
        if(!isset($_SESSION['username']))
            $f3->reroute('login');

        new ProjectDB();
        new CourseDB();

        $currentLinks = (CourseDB::getCurrentLinks($params['id']));

        $project = ProjectDB::getProject($params['id']);
        $courses = CourseDB::getCourseByProjectID($params['id']);

        $client = $project->getClient();

        $projectID = $params['id'];

        $f3->set('project', $project);
        $f3->set('client', $client);
        $f3->set('projectID', $projectID);
        $f3->set('courses', $courses);
        $f3->set('currentLinks', $currentLinks);

        echo Template::instance()->render('views/summary_pages/project_summary.html');
    });

    /**
     * Route for a course summary page
     * Takes an id to search the database 
     * for a course
     * 
     * @route GET|POST 
     * @alias /views/summary_pages/course_summary.html
     * @param $f3 Base
     * @param $params array
     */
    $f3->route('GET /course-summary/@id', function($f3, $params) {
        if(!isset($_SESSION['username']))
            $f3->reroute('login');

        new CourseDB();

        $course = CourseDB::getCourse($params['id']);
        $project = $course->getProject();
        $projectId = CourseDB::getProjectID($params['id']);

        $courseId = $params['id'];

        $f3->set('course', $course);
        $f3->set('courseId', $courseId);
        $f3->set('project', $project);
        $f3->set('projectId', $projectId);

        echo Template::instance()->render('views/summary_pages/course_summary.html');
    });

    /**
     * Route for a delete project page
     * Takes a project id to get the project
     * information from the database
     * 
     * Deletes a project as well as the associated
     * courses
     * 
     * @route GET|POST 
     * @alias /views/delete-project.html
     * @param $f3 Base
     * @param $params array
     */
    $f3->route('GET|POST /delete-project/@id', function($f3, $params){
        if(!isset($_SESSION['username']))
            $f3->reroute('login');

        new ProjectDB();
        new CourseDB();

        $projectId = $params['id'];

        $project = ProjectDB::getProject($params['id']);
        $courses = CourseDB::getCourseByProjectID($params['id']);
        $client = $project->getClient();

        $f3->set('projectId', $projectId);
        $f3->set('project', $project);
        $f3->set('client', $client);

        // delete a project and reroute back to home page
        if(isset($_POST['delete'])) {
            ProjectDB::deleteProject($projectId);

            // each project has a list of courses
            // so we loop through them all and delete them
            foreach($courses as $course)
                CourseDB::deleteCourse($course->getCourseId());

            $f3->reroute('/');
        }

        // just reroute back to main page
        if(isset($_POST['cancel'])) {
            $f3->reroute('/');
        }

        echo Template::instance()->render('views/delete-project.html');
    });

    /**
     * Route for a delete course page
     * Takes a course id to get the course
     * information from the database
     * 
     * Deletes a course
     * 
     * @route GET|POST 
     * @alias /views/delete-course.html
     * @param $f3 Base
     * @param $params array
     */
    $f3->route('GET|POST /delete-course/@id', function($f3, $params) {
        if(!isset($_SESSION['username']))
            $f3->reroute('login');
        
        new CourseDB();

        $courseId = $params['id'];
        $course = CourseDB::getCourse($params['id']); // get the course from the id

        $f3->set('course', $course);

        // delete course, then reroute to main page
        if(isset($_POST['delete'])) {
            CourseDB::deleteCourse($courseId);
            $f3->reroute('/');
        }

        // just reroute back to main page
        if(isset($_POST['cancel'])) {
            $f3->reroute('/');
        }

        echo Template::instance()->render('views/delete-course.html');
    });

    /**
     * Route for the error page
     * 
     * If a page is not found, the error page
     * gets displayed
     *
     * @alias /views/error.html
     * @param $f3 Base
     */
    $f3->set('ONERROR', function($f3) {
        if($f3->get('ERROR.code') == '404')
            echo Template::instance()->render('views/error.html');
    });

    $f3->run();
?>
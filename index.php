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


    // Default route
    $f3->route('GET|POST /', function($f3) {
        
        $template = new Template();

        $db = new ProjectDB();
        $projects = $db->getAllProjects();

        $f3->set('projects', $projects);

        echo $template->render('views/home.html');
    });

    // Login route
    $f3->route('GET|POST /login', function($f3) {
        if(isset($_POST['submit'])) {

            $errors = array();

            //include('models/login-validation.php');

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

    $f3->route('GET|POST /new-class/@id', function($f3, $params) {
        $quarters = array("fall", "winter", "spring", "summer");
        $f3->set("quarters", $quarters);

        new CourseDB();
        $course = CourseDB::getCourse($params['id']);

        $update = false; // update flag to see if we need to update course

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
                $errors['courseID'] = 'Must be a 4 digit number';

            if(!validQuarter($_POST['quarter']))
                $errors['quarter'] = 'Invalid course quarter';

            if(!validDate($_POST['year']))
                $errors['year'] = "Invalid year: must be ".date('Y')." or greater";

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

                if(!$update)
                    CourseDB::insertCourse($course);

                else {
                    CourseDB::updateCourse($course, $params['id']);
                    $f3->reroute('/course-summary/@id');
                }

                $f3->set('course', $course);
                //$_SESSION['course'] = $course;

                $f3->reroute('/project-summary/@id');
            }
        }

        echo Template::instance()->render('views/forms/class.html');
    });

    $f3->route('GET|POST /new-project', function($f3) {
        require('models/address-helpers.php');
        $f3->set('states', $states);

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

            if(empty($_POST['projectDescription']))
                $errors['description'] = 'Please enter a brief description';

            if(empty($_POST['companyName']))
                $errors['company'] = 'Please enter a company name';

            if(!validUrl($_POST['companyWebsite']))
                $errors['website'] = 'Please enter a valid url';

            if(empty($_POST['address']))
                $errors['address'] = 'Please enter an address';

            if(!validZip($_POST['zipcode']))
                $errors['zipcode'] = 'Please enter a valid zip';

            if(empty($_POST['city']))
                $errors['city'] = 'Please enter a city';

            if(empty($_POST['clientName']))
                $errors['clientName'] = 'Please enter the client\'s name';

            if(empty($_POST['clientJobTitle']))
                $errors['clientJobTitle'] = 'Please enter client\'s job title';

            if(!validEmail($_POST['clientEmail']))
                $errors['clientEmail'] = 'Please enter a valid client email';

            if(!validPhone($_POST['clientPhoneNumber']))
                $errors['clientPhone'] = 'Invalid format, must be: 123-456-8910';

            $f3->set('title', $_POST['projectTitle']);
            $f3->set('description', $_POST['projectDescription']);
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
                                        'pending',
                                        $client);

                new ProjectDB();

                // if id is not set, insert new
                // row, otherwise just update the row
                if(!isset($_GET['id']))
                    ProjectDB::insertProject($project);
                else {
                    ProjectDB::updateProject($project, $_GET['id']);
                }

                $f3->reroute('/');
            }
        }

        echo Template::instance()->render('views/forms/project_info.html');
    });

    $f3->route('GET /project-summary/@id', function($f3, $params) {
        new ProjectDB();
        new CourseDB();

        $project = ProjectDB::getProject($params['id']);
        $courses = CourseDB::getCourseByProjectID($params['id']);

        $client = $project->getClient();

        $projectID = $params['id'];

        $f3->set('project', $project);
        $f3->set('client', $client);
        $f3->set('projectID', $projectID);
        $f3->set('courses', $courses);

        echo Template::instance()->render('views/summary_pages/project_summary.html');
    });

    $f3->route('GET /course-summary/@id', function($f3, $params) {

        new CourseDB();

        $course = CourseDB::getCourse($params['id']);
        $project = $course->getProject();

        $courseId = $params['id'];

        $f3->set('course', $course);
        $f3->set('courseId', $courseId);
        $f3->set('project', $project);

        echo Template::instance()->render('views/summary_pages/course_summary.html');
    });

    $f3->route('GET|POST /delete-project/@id', function($f3, $params){
        new ProjectDB();
        new CourseDB();

        $projectId = $params['id'];

        $project = ProjectDB::getProject($params['id']);
        $courses = CourseDB::getCourseByProjectID($params['id']);
        $client = $project->getClient();

        $f3->set('projectId', $projectId);
        $f3->set('project', $project);
        $f3->set('client', $client);

        if(isset($_POST['delete'])) {
            ProjectDB::deleteProject($projectId);

            foreach($courses as $course)
                CourseDB::deleteCourse($course->getCourseId());

            $f3->reroute('/');
        }

        if(isset($_POST['cancel'])) {
            $f3->reroute('/');
        }

        echo Template::instance()->render('views/delete.html');
    });

    // Error page
    $f3->set('ONERROR', function($f3) {
        echo Template::instance()->render('views/error.html');
    });

    $f3->run();
?>
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

        $f3->set("projects", $projects);

        echo $template->render('views/home.html');
    });

    // Login route
    $f3->route('GET|POST /login', function($f3) {

        if(isset($_POST['submit'])) {
            $errors = array();

            include('models/login-validation.php');

            if(!validEmail($_POST['username']))
                $errors['username'] = 'Username must be a valid email';

            $f3->set('username', $_POST['username']); 
            $f3->set('errors', $errors);

            if(empty($errors)) {
                $_SESSION['username'] = $_POST['username'];

                $f3->reroute('/new-project');
            }
        }
        
        echo Template::instance()->render('views/login.html');
    });

    $f3->route('GET|POST /new-class', function($f3) {

        $quarters = array("fall", "winter", "spring", "summer");
        $f3->set("quarters", $quarters);

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

                $course = new Course($_POST['courseID'],
                                    $_POST['quarter'],
                                    $_POST['year'],
                                    $_POST['instructor']);

                $course->setGithub($_POST['github']);
                $course->setTrello($_POST['trello']);
                $course->setUrl($_POST['url']);
                $course->setUsername($_POST['username']);
                $course->setPassword($_POST['password']);
                $course->setInstructorNotes($_POST['notes']);

                $_SESSION['course'] = serialize($course);

                $f3->reroute('/new-project'); 
            }
        }

        echo Template::instance()->render('views/forms/class.html');
    });

    $f3->route('GET|POST /new-project', function($f3) {
        require('models/address-helpers.php');
        $f3->set('states', $states);

        if(isset($_POST['submit'])) {
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
                $errors['clientPhone'] = 'Invalid format, must be: 123-4567-8910';
            
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

                $course = unserialize($_SESSION['course']);

                $client = new Client($_POST['clientName'],
                                    $_POST['clientJobTitle'],
                                    $_POST['clientEmail'],
                                    $_POST['clientPhoneNumber'],
                                    $_POST['companyName'],
                                    $_POST['companyWebsite'],
                                    $_POST['address'],
                                    $_POST['state'],
                                    $_POST['city'],
                                    $_POST['zipcode']);

                $course->setProjectTitle($_POST['projectTitle']);
                $course->setDescription($_POST['projectDescription']);

                $_SESSION['course'] = serialize($course);
                $_SESSION['client'] = serialize($client);

                $f3->reroute('/course-summary'); // TODO: change route 
            }
        }

        echo Template::instance()->render('views/forms/project_info.html');
    });

    $f3->route('GET /project-summary', function($f3) {

        if(!empty($_SESSION['course']) && !empty($_SESSION['client'])) {
            $course = unserialize($_SESSION['course']);
            $client = unserialize($_SESSION['client']);

            $f3->set('course', $course);
            $f3->set('client', $client);

            echo Template::instance()->render('views/summary_pages/project_summary.html');
        }  else {
            $f3->reroute('/');
        }

        
    });

    $f3->route('GET /course-summary', function($f3) {
       
        if(!empty($_SESSION['course']) && !empty($_SESSION['client'])) {
            $course = unserialize($_SESSION['course']);
            $client = unserialize($_SESSION['client']);

            $f3->set('course', $course);
            $f3->set('client', $client);

            echo Template::instance()->render('views/summary_pages/course_summary.html');
        } else {
            $f3->reroute('/');
        }
    });

    // Error page
    $f3->set('ONERROR', function($f3) {
        echo Template::instance()->render('views/error.html');
    });
    
    $f3->run();
?>
<?php
    session_start();

    // Turn on error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // Require autoload
    require_once('vendor/autoload.php');

    // Require validations
    require_once('models/validations.php');

    // Create fat-free instance
    $f3 = Base::instance();

    // Set debug level to dev
    $f3->set('DEBUG', 3);

    // Default route
    $f3->route('GET|POST /', function() {
        $template = new Template();
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

            $f3->set('title', $_POST['projectTitle']);
            $f3->set('description', $_POST['projectDescription']);
            $f3->set('company', $_POST['companyName']);
            $f3->set('website', $_POST['companyWebsite']);
            $f3->set('errors', $errors);

            if(empty($errors)) {

                $_SESSION['title'] = $_POST['projectTitle'];
                $_SESSION['description'] = $_POST['projectDescription'];
                $_SESSION['comnpany'] = $_POST['companyName'];
                $_SESSION['website'] = $_POST['companyWebsite'];

                $f3->reroute('/'); // TODO: change route 
            }
        }

        echo Template::instance()->render('views/forms/project_info.html');
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
                $course->setPasswowrd($_POST['password']);
                $course->setInstructorNotes($_POST['notes']);

                $_SESSION['course'] = $course;

                $f3->reroute('/'); // TODO: change route 
            }
        }

        echo Template::instance()->render('views/forms/class.html');
    });

    $f3->route('GET /project-summary', function() {
        echo Template::instance()->render('views/summary_pages/project_summary.html');
    });

    // Error page
    $f3->set('ONERROR', function($f3) {
        echo Template::instance()->render('views/error.html');
    });
    
    $f3->run();
?>
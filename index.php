<?php
    session_start();

    // Turn on error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    // Require autoload
    require_once('vendor/autoload.php');

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

    $f3->route('GET /new-project', function() {
        echo Template::instance()->render('views/forms/project_info.html');
    });

    $f3->route('GET|POST /new-class', function($f3) {

        $quarters = array("fall", "winter", "spring", "summer");
        $f3->set("quarters", $quarters);

        if(isset($_POST['submit'])) {
            include('models/new-class-validation.php');

            $errors = array();

            if(!validCourseId($_POST['courseID']))
                $errors['courseID'] = 'Invalid course id: must be a 4 digit number';

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

            $f3->set('courseID', $_POST['courseID']);
            $f3->set('quarter', $_POST['quarter']);
            $f3->set('year', $_POST['year']);
            $f3->set('github', $_POST['github']);
            $f3->set('trello', $_POST['trello']);
            $f3->set('url', $_POST['url']);
            $f3->set('errors', $errors);

            if(empty($errors)) {
                $_SESSION['courseID'] = $_POST['courseID'];
                $_SESSION['quarter'] = $_POST['quarter'];
                $_SESSION['year'] = $_POST['year'];
                $_SESSION['github'] = $_POST['github'];
                $_SESSION['trello'] = $_POST['trello'];
                $_SESSION['url'] = $_POST['url'];

                $f3->reroute('/'); // TODO: change route 
            }
        }


        echo Template::instance()->render('views/forms/class.html');
    });

    // Error page
    $f3->set('ONERROR', function($f3) {
        echo Template::instance()->render('views/error.html');
    });
    
    $f3->run();
?>
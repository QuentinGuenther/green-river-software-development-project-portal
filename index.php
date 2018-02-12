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

    $f3->route('GET /new-class', function() {
        echo Template::instance()->render('views/forms/class.html');
    });

    // Error page
    $f3->set('ONERROR', function($f3) {
        echo Template::instance()->render('views/error.html');
    });
    
    $f3->run();
?>
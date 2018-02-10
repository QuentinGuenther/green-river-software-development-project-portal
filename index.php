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
    $f3->route('GET /login', function() {
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
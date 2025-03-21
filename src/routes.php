<?php
    use Symfony\Component\Routing;



    // import all controllers
    $controller_files = glob(__DIR__ . '/controllers/*.php');

    foreach ($controller_files as $file) {
        require_once $file;
    }

    // create routes
    $routes = new Routing\RouteCollection();

    $routes->add('login', new Routing\Route('/login', array(
        '_controller' => 'Controllers\LoginController::login'
    )));

    $routes->add('signup', new Routing\Route('/signup', array(
        '_controller' => 'Controllers\LoginController::signup'
    )));

    $routes->add('dashboard', new Routing\Route('/dashboard', array(
        '_controller' => 'Controllers\DashboardController::dashboard'
    )));

    $routes->add('filter', new Routing\Route('/filter', array(
        '_controller' => 'Controllers\DashboardController::filter'
    )));

    return $routes;
?>
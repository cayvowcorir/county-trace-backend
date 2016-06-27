<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);


$container = $app->getContainer();



// Set up dependencies
require __DIR__ . '/src/dependencies.php';

require __DIR__ . '/src/Migrations/Migration.php';


// Register middleware
require __DIR__ . '/src/middleware.php';


// Register routes
require __DIR__ . '/src/routes.php';


require __DIR__."/classes/Models/County.php";
require __DIR__."/classes/Models/seeder.php";
require __DIR__."/classes/Models/Concern.php";
require __DIR__."/classes/Models/User.php";
require __DIR__."/classes/Models/Session.php";
require __DIR__."/classes/Models/Project.php";
require __DIR__."/classes/Models/Leader.php";
require __DIR__."/classes/Models/ConcernImage.php";

require __DIR__."/classes/Controllers/Controller.php";
require __DIR__."/classes/Controllers/AuthController.php";
require __DIR__."/classes/Controllers/BlocksController.php";
require __DIR__."/classes/Controllers/ProjectsController.php";
require __DIR__."/classes/Controllers/ConcernsController.php";
require __DIR__."/classes/Controllers/LeadersController.php";


// Run

$app->run();



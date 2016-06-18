<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

$container['db'] = function ($container) {

    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();



    return $capsule;

};

$container[Controllers\BlocksController::class] = function ($c) {
    // $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('db')->table('block');
    return new Controllers\BlocksController($c, $logger, $table);
};
$container[Controllers\ProjectsController::class] = function ($c) {
    // $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('db')->table('project');
    return new Controllers\ProjectsController($c, $logger, $table);
};
$container[Controllers\ConcernsController::class] = function ($c) {
    // $view = $c->get('view');
    $logger = $c->get('logger');
    $table = $c->get('db')->table('publicConcern');
    return new Controllers\ConcernsController($c, $logger, $table);
};

$container[Controllers\AuthController::class] = function ($c) {
    $logger = $c->get('logger');
    $table = $c->get('db')->table('citizen');
    return new Controllers\AuthController($c, $logger, $table);
};

$container[Controllers\LeadersController::class] = function ($c) {
    $logger = $c->get('logger');
    $table = $c->get('db')->table('leader');
    return new Controllers\LeadersController($c, $logger, $table);
};


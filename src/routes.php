<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
$app->get('/create', function(){
    $widget = new \App\Model\User();
    $widget->serial_number = 123;
    $widget->name = 'My Test Widget';
    $widget->save();
    echo 'Created!';
});

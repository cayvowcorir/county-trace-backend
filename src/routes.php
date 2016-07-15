<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;



// $app->add(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
//     // Use the PSR 7 $response object
//     $allowed_http_origins   = array(
//                             'http://localhost:9000', 'http://localhost:9001'
//                           );
//     $requestHeader=$request->getHeader('Origin');
//     if (in_array($requestHeader, $allowed_http_origins)){
//       $response = $response->withHeader('Access-Control-Allow-Origin', $requestHeader);
//       return $next($request, $response1);
//     }


// });

$middleware=function ($request, $response, $next) {
  $allowed_http_origins   = array('http://localhost:9000', 'http://localhost:9001');

  $requestHeader=$request->getHeader('origin');
  if (in_array($requestHeader[0], $allowed_http_origins)){
    $response = $response->withHeader('Access-Control-Allow-Origin', $requestHeader);
  }

  $response =$response->withHeader('Access-Control-Allow-Methods','GET, PUT,POST,DELETE, OPTIONS');
  $response = $response->withHeader('Access-Control-Allow-Headers','origin, content-type, X-Api-Token, accept');
  $response = $response->withHeader('Access-Control-Max-Age', '1728000');
  $response = $response->withHeader('Content-Type','application/json');
  $response = $response->withHeader('Access-Control-Allow-Credentials','true');
  $response = $next($request, $response);

  return $response;
};

$elevatedPrivilege=function ($request, $response, $next) {
  $allowed_http_origin   = 'http://0.0.0.0:9001';

  $requestHeader=$request->getHeader('origin');

  if ($requestHeader[0]==$allowed_http_origin){
    $response = $response->withHeader('Access-Control-Allow-Origin', $requestHeader);
    $response =$response->withHeader('Access-Control-Allow-Methods','GET, PUT,POST,DELETE, OPTIONS');
  $response = $response->withHeader('Access-Control-Allow-Headers','origin, content-type, X-Api-Token, accept');
  $response = $response->withHeader('Access-Control-Max-Age', '1728000');
  $response = $response->withHeader('Content-Type','application/json');
  $response = $response->withHeader('Access-Control-Allow-Credentials','true');
  $response = $next($request, $response);
  }
  else{
    $errorResponse=Array('Status'=>'Not Authorized');
    $response=$response->withJson($errorResponse);
  }



  return $response;
};

$authentication=function($request, $response, $next){
  if(!$request->getHeader('X-Api-Token')){
     $authError=Array("Error"=>"Not Authorized");
    $response=$response->withJson($authError);
  }
  else{
    $response =$next($request, $response);

  }
  return $response;
};


$app->get('/', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


$app->post('/counties', 'Controllers\BlocksController:postCounty')->add($middleware);



$app->options('/{level}/list', function ($request, $response, $args) {})->add($middleware);

$app->get('/{level}/list', 'Controllers\BlocksController:getBlockList')->add($middleware);

$app->get('/{blockId}/projects', 'Controllers\ProjectsController:getProjects')->add($middleware);
$app->options('/{blockId}/projects', function ($request, $response, $args) {})->add($middleware);

$app->get('/projects/{id}', 'Controllers\ProjectsController:getProject')->add($middleware);
$app->options('/projects/{id}', function ($request, $response, $args) {})->add($middleware);

$app->get('/concerns/get[/{blockId}]', 'Controllers\ConcernsController:getConcerns')->add($middleware);
$app->options('/concerns/get[/{blockId}]', function ($request, $response, $args) {})->add($middleware);

$app->post('/concerns/new', 'Controllers\ConcernsController:newConcern')->add($middleware)->add($authentication);
$app->options('/concerns/new', function ($request, $response, $args) {
})->add($middleware);

$app->get('/concerns/{id}', 'Controllers\ConcernsController:getConcern')->add($middleware)->add($authentication);
$app->options('/concerns/{id}', function ($request, $response, $args) {
})->add($middleware);

$app->post('/concerns/{id}/images', 'Controllers\ConcernsController:postConcernImages')->add($middleware)->add($authentication);
$app->get('/concerns/{id}/images', 'Controllers\ConcernsController:getConcernImages')->add($middleware)->add($authentication);
$app->options('/concerns/{id}/images', function ($request, $response, $args) {
})->add($middleware);

$app->get('/images/concerns/{name}', 'Controllers\ConcernsController:getConcernImage')->add($middleware)->add($authentication);
$app->options('/images/concerns/{name}', function ($request, $response, $args) {
})->add($middleware);

$app->get('/concerns', 'Controllers\ConcernsController:getAllConcerns')->add($middleware)->add($authentication);
$app->options('/concerns', function ($request, $response, $args) {
})->add($middleware);

$app->post('/projects/new', 'Controllers\ProjectsController:newProject')->add($middleware)->add($authentication);
$app->options('/projects', function ($request, $response, $args) {})->add($middleware);

$app->get('/projects/get/all', 'Controllers\ProjectsController:getAllProjects')->add($middleware)->add($authentication);
$app->options('/projects/get/all', function ($request, $response, $args) {})->add($middleware);


$app->post('/users/new', 'Controllers\AuthController:addUser')->add($middleware);
$app->options('/users/new', function ($request, $response, $args) {})->add($middleware);

$app->post('/users/login', 'Controllers\AuthController:loginUser')->add($middleware);
$app->options('/users/login', function ($request, $response, $args) {})->add($middleware);

$app->post('/users/logout', 'Controllers\AuthController:logout')->add($middleware)->add($authentication);
$app->options('/users/logout', function ($request, $response, $args) {})->add($middleware);

$app->post('/leaders/login', 'Controllers\AuthController:loginLeader')->add($middleware);
$app->options('/leaders/login', function ($request, $response, $args) {})->add($middleware);

/*-----------------------------------------
-------------ADMIN SECTION-----------------
-------------------------------------------*/

//Admin View of users-Elevated privileges
$app->get('/citizen', 'Controllers\AuthController:getAllUsers')->add($elevatedPrivilege);
$app->get('/citizen/{id}', 'Controllers\AuthController:getUser')->add($elevatedPrivilege);
$app->put('/citizen/{id}', 'Controllers\AuthController:modifyUser')->add($elevatedPrivilege);
$app->get('/block', 'Controllers\BlocksController:getBlockList')->add($elevatedPrivilege);
$app->get('/block/{id}', 'Controllers\BlocksController:getBlock')->add($elevatedPrivilege);
$app->get('/project', 'Controllers\BlocksController:getBlockList')->add($elevatedPrivilege);
$app->get('/leader', 'Controllers\LeadersController:getLeaders')->add($elevatedPrivilege);
$app->post('/leader', 'Controllers\AuthController:adminAddLeader')->add($elevatedPrivilege);
$app->options('/users', function ($request, $response, $args) {})->add($elevatedPrivilege);
$app->options('/leader', function ($request, $response, $args) {})->add($elevatedPrivilege);

//Admin Creation of users-Elevated privileges
$app->post('/users', 'Controllers\AuthController:adminAddUser')->add($elevatedPrivilege);






// function($request, $response, $args){
//   $response = $response->withHeader('Access-Control-Allow-Origin', '*');
//   $headers = $response->getHeaders();
//   $data = array('name' => 'Bob', 'age' => 40);
//   $newResponse = $response->withJson($data);
//   return $newResponse;
// }

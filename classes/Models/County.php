<?php

namespace Models;

// use Slim\Views\Twig;
// use Psr\Log\LoggerInterface;
// use Illuminate\Database\Query\Builder;
// use Psr\Http\Message\ServerRequestInterface as Request;
// use Psr\Http\Message\ResponseInterface as Response;
// use Illuminate\Database\Eloquent;
// use Illuminate\Database\Eloquent\Model;


class County
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $fillable = array('name');

    public function governor() {
        return $this->hasOne('Governor'); // this matches the Eloquent model
    }
    public function deputyGovernor() {
        return $this->hasOne('DeputyGovernor');
    }

    public function projects() {
        return $this->hasMany('Projects');
    }
    function getCounties(){
      // $container;
      // $capsule=new Capsule;
      // $users = $capsule::table('users')->get();
      // $container = $app->getContainer();
      // // $user=$container->get('db')->$capsule->table('user');
      // return $container;
    }



    // public function p() {
    //     return $this->belongsToMany('');
    // }



  // private $logger;
  //   protected $table;

  //   public function __construct(
  //       LoggerInterface $logger,
  //       Builder $table
  //   ) {
  //       $this->view = $view;
  //       $this->logger = $logger;
  //       $this->table = $table;
  //   }

  //
  // function getCounty($id){

  // }
  // function addCounty($id){

  // }

    // public function __invoke(Request $request, Response $response, $args)
    // {
    //     $widgets = $this->table->get();

    //     $this->view->render($response, 'app/index.twig', [
    //         'widgets' => $widgets
    //     ]);

    //     return $response;
    // }
}

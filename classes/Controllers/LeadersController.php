<?php

namespace Controllers;

use Controllers\AuthController;
use Controllers\Controller;
use Models\Leader;

class LeadersController extends Controller {



/*---------------------------------
--------Get All Leaders-----------
-------------------------------*/

   public function getLeaders($request, $response, $args) {

      $list=$this->table->get();
      $response=$response->withJson($list);
      return $response;

   }

    public function getBlockLeader($request, $response, $args) {
    if($args['block']){
      $block=$args['block'];
      $list=$this->table->where('blockId', '=', $block)->get();
      $response=$response->withJson($list);
    }
    else{
      $status=Array("Status"=>"No Block Specified");
      $response=$response->withJson($status);
    }
      return $response;

   }
}

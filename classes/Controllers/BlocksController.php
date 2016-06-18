<?php

namespace Controllers;

use Controllers\AuthController;
use Controllers\Controller;
use Models\Concern;

class BlocksController extends Controller {



   public function getCounties($request, $response, $args) {
        $data= $this->table->get();
        $newResponse = $response->withJson($data);
        return $newResponse;
   }

/*---------------------------------
--------Get Block List-----------
-----------------------------------*/

   public function getBlockList($request, $response, $args) {
    if($args){
      $level=$args['level'];
      $list=$this->table->where('level', 'like', $level)->get();
      $response=$response->withJson($list);
    }
    else{
      $list=$this->table->get();
      $response=$response->withJson($list);
    }
      return $response;

   }




   /*---------------------------------
--------Get Individual Block-----------
-----------------------------------*/

   public function getBlock($request, $response, $args) {
      $blockId=$args['id'];

      $list=$this->table->where('blockId', 'like', $blockId)->get();
      $response=$response->withJson($list[0], 200, JSON_FORCE_OBJECT);

      return $response;

   }



   public function postCounty($request, $response, $args) {
        $data=$request->getParsedBody();
        $countyName=$data['governor'];
        $data2 = array('name' => $countyName );

        $newResponse = $response->withJson($data2);
        return $newResponse;

   }

}

<?php

namespace Controllers;


use Controllers\AuthController;
use Controllers\Controller;
use Models\Project;


class ProjectsController extends Controller {


   public function getProjects($request, $response, $args) {
        $data= $this->table->where('blockId', '=', $args['blockId'])->get();
        $newResponse = $response->withJson($data);
        return $newResponse;
   }

   public function getAllProjects($request, $response, $args) {
        $token=$request->getHeader('X-Api-Token');
        $citizenId=$this->ci->get('Controllers\AuthController')->authorize($token);

        if($citizenId==null){
          $response=$response->withJson(Array("Error"=>"Not Authorized"));
        }
        else{
          $concerns=$this->table->get();

          $arraysize=count($concerns);
          for($i=0; $i<$arraysize; $i++){
            $citizenDetails=$this->ci->get('db')->table('citizen')->where('id', '=', $concerns[$i]->citizenId)->get();
            $concerns[$i]->citizenName=$citizenDetails[0]->citizenName;
            $blockDetails=$this->ci->get('db')->table('block')->where('blockId', '=', $concerns[$i]->blockId)->get();
            $concerns[$i]->blockName=$blockDetails[0]->name;
          }
        }
        $response=$response->withJson($concerns);
        return $response;
   }

   public function newProject($request, $response, $args) {
        $token=$request->getHeader('X-Api-Token');
        $data=$request->getParsedBody();
        $session=$this->ci->get('Controllers\AuthController')->authorize($token);

        if($session==null){
          $response=$response->withJson(Array("Error"=>"Not Authorized"));
        }
        else{
          $project=new Project;
          $project->setAttribute('projectName',$data['name']);
          $project->setAttribute('projectDescription', $data['description'] );
          $project->setAttribute('projectStatus', $data['status']);
          $project->setAttribute('blockId', $data['blockId']);
          $project->setAttribute('projectBudget', $data['budget']);
          $project->setAttribute('citizenId', $data['citizenId']);

          $success = array('code' => '1' );
          $failiure = array('code' => '-1' );
          // error_log(print_r($concern, TRUE));
          try{
            $project->saveOrFail();
            $response = $response->withJson($success);
          }
          catch(Exception $e){
            $response = $response->withJson($failiure);
          };
        }

        return $response;

   }

   public function getProject($request, $response, $args) {

        $projectId=$args['id'];
        $token=$request->getHeader('X-Api-Token');
        $citizenId=$this->ci->get('Controllers\AuthController')->authorize($token);

        if($citizenId==null){
          $response=$response->withJson(Array("Error"=>"Not Authorized"));
          return $response;
        }
        else{
          $projectDetails=$this->table->where('projectId', '=', $projectId)->get();
          $citizenDetails=$this->ci->get('db')->table('citizen')->where('id', '=', $citizenId)->get();
          $blockDetails=$this->ci->get('db')->table('block')->where('blockId', '=', $projectDetails[0]->blockId)->get();
          $projectDetails[0]->citizenName=$citizenDetails[0]->citizenName;
          $projectDetails[0]->blockName=$blockDetails[0]->name;
        }

        $newResponse = $response->withJson($projectDetails[0], 200, JSON_FORCE_OBJECT);
        return $newResponse;

   }

}

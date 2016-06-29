<?php

namespace Controllers;

use Controllers\AuthController;
use Controllers\Controller;
use Models\Concern;
use Models\ConcernImage;



class ConcernsController extends Controller {


   /*-------------------------------------
   ---------------get all concerns------
   ---------------------------------------*/

   public function getConcerns($request, $response, $args) {
      if($args){
        $blockId=$args['blockId'];
      }
      else{
        $token=$request->getHeader('X-Api-Token');
        $citizenId=$this->ci->get('Controllers\AuthController')->authorize($token);

        if($citizenId==null){
          $response=$response->withJson(Array("Error"=>"Not Authorized"));
          return $response;
        }
        else{
          $leaderDetails=$this->ci->get('db')->table('leader')->where('citizenId', '=', $citizenId)->get();
          $blockId=$leaderDetails[0]->blockId;
        }
      }
      $data= $this->table->where('blockId', '=', $blockId)->get();

        $newResponse = $response->withJson($data);
        return $newResponse;

   }

public function getAllConcerns($request, $response, $args){
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


   /*-------------------------------------
   ---------------get one concern's details------
   ---------------------------------------*/
 public function getConcern($request, $response, $args) {

        $concernId=$args['id'];
        $token=$request->getHeader('X-Api-Token');
        $citizenId=$this->ci->get('Controllers\AuthController')->authorize($token);

        if($citizenId==null){
          $response=$response->withJson(Array("Error"=>"Not Authorized"));
          return $response;
        }
        else{
          $concernDetails=$this->table->where('publicConcernId', '=', $concernId)->get();
          $citizenDetails=$this->ci->get('db')->table('citizen')->where('id', '=', $citizenId)->get();
          $blockDetails=$this->ci->get('db')->table('block')->where('blockId', '=', $concernDetails[0]->blockId)->get();
          $concernDetails[0]->citizenName=$citizenDetails[0]->citizenName;
          $concernDetails[0]->blockName=$blockDetails[0]->name;
        }

        $newResponse = $response->withJson($concernDetails[0], 200, JSON_FORCE_OBJECT);
        return $newResponse;

   }





   /*-------------------------------------
   ---------------Create New COncern------
   ---------------------------------------*/



   public function newConcern($request, $response, $args) {
      $token=$request->getHeader('X-Api-Token');

      $session=$this->ci->get('Controllers\AuthController')->authorize($token);

      if($session==null){
        $response=$response->withJson(Array("Error"=>"Not Authorized"));
      }
      else{
        $data=$request->getParsedBody();
        $concern=new Concern;
        $concern->setAttribute('publicConcernName',$data['title']);
        $concern->setAttribute('publicConcernDescription', $data['description'] );
        $concern->setAttribute('blockId', $data['blockId']);
        $concern->setAttribute('addressee', $data['addressee']);
        $concern->setAttribute('citizenId', (int)$session['citizenId']);

        $failiure = array('code' => '-1' );
        // error_log(print_r($concern, TRUE));
        try{
          $concern->saveOrFail();
          $response = $response->withJson(Array('code' => '1', 'concernId'=>$concern->publicConcernId));
        }
        catch(Exception $e){
          $response = $response->withJson($failiure);
        };
      }
        return $response;

   }

   /*==============================================
   =============Post Concern Images================
   ================================================*/
   public function postConcernImages($request, $response, $args){
      $token=$request->getHeader('X-Api-Token');
      $session=$this->ci->get('Controllers\AuthController')->authorize($token);

      if($session==null){
        $response=$response->withJson(Array("Error"=>"Not Authorized"));
      }
      else{

        $files = $request->getUploadedFiles();
        $concernId=$args['id'];

        $newfile = $files['file'];

        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $extension = explode("/",$newfile->getClientMediaType());
            $imageName="concern".microtime().".".$extension[1];
            $imageName = str_replace(' ', '', $imageName);

            $concernImage=new ConcernImage;
            $concernImage->setAttribute('publicConcernId',$concernId);
            $concernImage->setAttribute('imageUrl', $imageName);
            try{
              $concernImage->saveOrFail();
               try{
              $newfile->moveTo(__DIR__ . "/../../public/images/concerns/$imageName");
              $status=Array("Status"=>"Success");

              }
              catch (Exception $e){
                $status=Array("Status"=>"Fail");
              }
            }
            catch (Exception $e){
              $status=Array("Status"=>"Fail");
            }




        }
        else $status=Array("Status"=>"Fail");

        return $response->withJson($status);


      }

   }

   /*=======================================
   ===============Get all Concern Image Urls====
   ==============================================*/

   public function getConcernImages($request, $response, $args){
      $token=$request->getHeader('X-Api-Token');
      $session=$this->ci->get('Controllers\AuthController')->authorize($token);

      if($session==null){
        $response=$response->withJson(Array("Error"=>"Not Authorized"));
      }
      else{
        $concernId=$args['id'];
        $concernImages=$this->ci->get('db')->table('concernImages')->where('publicConcernId', '=', $concernId)->get();
        error_log(print_r(count($concernImages), True));
        $arrayCount=count($concernImages);
        $responseArray=[];
        for($i=0; $i<$arrayCount; $i++){
          array_push($responseArray,$concernImages[$i]->imageUrl);

        }
        error_log(print_r($responseArray, True));
        return $response->withJson($responseArray);

      }
   }


   /*=======================================
   ===============Get conern Image====
   ==============================================*/
    public function getConcernImage($request, $response, $args){
      $token=$request->getHeader('X-Api-Token');
      $session=$this->ci->get('Controllers\AuthController')->authorize($token);

      if($session==null){
        $response=$response->withJson(Array("Error"=>"Not Authorized"));
      }
      else{
        $concernImageName=$args['name'];
        $splitName=explode('.', $concernImageName);
        error_log(print_r($splitName,True));
        $dir=__DIR__ . "/../../public/images/concerns";
        error_log(print_r($dir,True));
        $image=file_get_contents($dir.$concernImageName);

        $response->withHeader('Content-Type', 'image/'.$splitName[1] );
        $response->withHeader('Content-Disposition', 'attachment');
        $response->withHeader('filename',$concernImageName);
        $response->withHeader('Content-Length',  filesize($image));


        ob_clean();
        flush();
        readfile($concernImageName);
        echo($concernImageName);

        return $response->withJson($responseArray);

      }
   }
}

//$counties = array("Mombasa","Kwale","Kilifi","Tana River","Lamu","Taita-Taveta","Garissa","wajir","Mandera","Marsabit","Isiolo","Meru","Tharaka-Nithi","Embu","Kitui","Machakos","Makueni","Nyandarua","Nyeri","Kirinyaga","Murang'a","Kiambu","Turkana","West Pokot","Samburu","Trans Nzoia","Uasin Gishu","Elgeyo-Marakwet","Nandi","Baringo","Laikipia","Nakuru","Narok","Kajiado","Kericho","Bomet","Kakamega","Vihiga","Bungoma","Busia","Siaya","Kisumu","Homa Bay","Migori","Kisii","Nyamira","Nairobi City" );


/*Db Seed Code*/
/*for ($i=0; $i<50; $i++){
        $concern=new Concern;
        error_log(print_r($data, TRUE));

         $concern->setAttribute('citizenName',substr(hash('sha256', mt_rand()), 0, 10));
        $concern->setAttribute('citizenEmail', substr(hash('sha256', mt_rand()), 0, 7).'@gmail.com');
        $concern->setAttribute('citizenPassword', substr(hash('sha256', mt_rand()), 0, 50));



        // $concern->setAttribute('publicConcernName',$data['title']);
        // $concern->setAttribute('publicConcernDescription', $data['description'] );
        // $concern->setAttribute('publicConcernImageUrl', $data['description']);
        // $concern->setAttribute('blockId', 1);
        // $concern->setAttribute('citizenId', 1);


        try{
          $bool=$concern->saveOrFail();
        }
        catch(Exception $e){
          error_log(print_r($e , TRUE));
        };
      }
*/

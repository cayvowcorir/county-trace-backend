<?php

namespace Controllers;

use Models\User;
use Models\Leader;
use Models\Session;
use Controllers\Controller;

class AuthController extends Controller
{

/*---------------------------------
--------Add User------------
-----------------------------------*/

    public function addUser($request, $response, $args)
    {
      $body=$request->getParsedBody();
      $user=new User;
      if($this->emailCheck($body['email'])){
        $serverResponse=Array('Status'=>'Email Exists');
      }
      else{

      $user->setAttribute('citizenName', $body['name']);
      $user->setAttribute('citizenEmail', $body['email']);
      $user->setAttribute('citizenPassword', password_hash($body['password'], PASSWORD_DEFAULT));

      try{
        $status=$user->saveOrFail();
        $token = $this->generateToken($user->getAttribute('id'), $request->getHeader('User-Agent'));
        $serverResponse=Array('Token'=>$token);
      }
      catch(Exception $e){

      }


    }
    $this->logger->info("Slim-Skeleton '/' route");
    return $response->withJson($serverResponse);
    }


/*------------------------
--------Login------------
-------------------------*/

    public function loginUser($request, $response, $args){
      //check if the user is already logged in
      if($this->authorize($request->getHeader('X-Api-Token'))){
         $this->endSession($request->getHeader('X-Api-Token'));
      }

          $body=$request->getParsedBody();
          $user=$this->table->where('citizenEmail', "=", $body['email'])->get();
          if($user[0]){
            //verify password
            $isValid = password_verify($body['password'], $user[0]->citizenPassword);
            if($isValid){
              $token = $this->generateToken($user[0]->id, $request->getHeader('User-Agent'));
              $status=Array("Token"=>$token);

              if($user[0]->citizenIsLeader==1){
                $block=$this->ci->get('db')->table('leader')->where('citizenId', '=', $user[0]->id)->get();
                $userInfo=Array("Email"=>$user[0]->citizenEmail,"id"=>$user[0]->id, "Name"=>$user[0]->citizenName,
                'Block'=>$block[0]->blockId);
              }
              else{
                $userInfo=Array("Email"=>$user[0]->citizenEmail, "Name"=>$user[0]->citizenName);
              }

              $response=$response->withJson($status + $userInfo);
            }
            else {
              $status=Array("Status"=>"Wrong Password");
              $response=$response->withJson($status);
            }
          }
          else{
            $status=Array("Status"=>"User Not Found");
            $response=$response->withJson($status);
          }

      return $response;
    }


    /*---------------------------------
--------Leader Login------------
-----------------------------------*/

    public function loginLeader($request, $response, $args){
      //check if the user is already logged in
      if($this->authorize($request->getHeader('X-Api-Token'))){
         $this->endSession($request->getHeader('X-Api-Token'));
      }

          $body=$request->getParsedBody();
          $user=$this->ci->get('db')->table('leader')->where('email', "=", $body['email'])->get();
          if($user[0]){
            //verify password
            $isValid = password_verify($body['password'], $user[0]->password);
            if($isValid){
              $token = $this->generateToken($user[0]->id, $request->getHeader('User-Agent'));

              $status=Array("Token"=>$token);
                $block=$this->ci->get('db')->table('block')->where('blockId', '=', $user[0]->blockId)->get();

                $userInfo=Array("Email"=>$user[0]->email,"id"=>$user[0]->id, "Name"=>$user[0]->name,
                "IsLeader"=>'1', 'Block'=>$block[0]->name);


              $newResponse=$response->withJson($status + $userInfo);
              error_log(print_r($response, TRUE));
            }
            else {
              $status=Array("Status"=>"Wrong Password");
              $response=$response->withJson($status);

            }
          }
          else{
            $status=Array("Status"=>"User Not Found");
            $response=$response->withJson($status);
          }
      return $response;
    }


/*---------------------------------
--------[ADMIN] Add User------------
-----------------------------------*/

    public function adminAddUser($request, $response, $args)
    {
      $body=$request->getParsedBody();
      $user=new User;
      if($this->emailCheck($body['citizenEmail'])){
        $serverResponse=Array('Status'=>'Email Exists');
      }
      else{

      $user->setAttribute('citizenName', $body['citizenName']);
      $user->setAttribute('citizenEmail', $body['citizenEmail']);
      $user->setAttribute('citizenPassword', password_hash($body['citizenPassword'], PASSWORD_DEFAULT));
      $user->setAttribute('citizenIsLeader', $body['citizenIsLeader']);
      $user->setAttribute('citizenIsAdmin', $body['citizenIsAdmin']);



      try{
        $status=$user->saveOrFail();
        $serverResponse=Array('Status'=>$status);
      }
      catch(Exception $e){

      }


    }
    return $response->withJson($serverResponse);
    }



    /*---------------------------------
--------[ADMIN] Add Leader------------
-----------------------------------*/

    public function adminAddLeader($request, $response, $args)
    {
      $body=$request->getParsedBody();
      $leader=new Leader;
      if($this->emailCheck($body['email'])){
        $serverResponse=Array('Status'=>'Email Exists');
      }
      else{

      $leader->setAttribute('name', $body['name']);
      $leader->setAttribute('email', $body['email']);
      $leader->setAttribute('password', password_hash($body['password'], PASSWORD_DEFAULT));
      $leader->setAttribute('position', $body['position']);
      $leader->setAttribute('blockId', $body['blockId']);



      try{
        $status=$leader->saveOrFail();
        $serverResponse=Array('Status'=>$status);
      }
      catch(Exception $e){

      }


    }
    return $response->withJson($serverResponse);
    }

/*---------------------------------
--------[ADMIN] Get Users------------
-----------------------------------*/

    public function getAllUsers($request, $response, $args){
        $data= $this->table->get();
        $newResponse = $response->withJson($data);
        return $newResponse;
    }


/*---------------------------------
--------[ADMIN] Get Single user-----------
-----------------------------------*/

    public function getUser($request, $response, $args){
        $data= $this->table->where('id', "=", $args['id'])->get();
        $newResponse = $response->withJson($data[0], 200, JSON_FORCE_OBJECT);
        return $newResponse;
    }


/*---------------------------------
--------logout User------------
-----------------------------------*/

     public function logout($request, $response, $args){
        $token=$request->getHeader('X-Api-Token');
        if($this->endSession($token)){
          $status=Array('Status'=>"Logged Out");
        }
        else{
          $status=Array('Status'=>"Error");
        }
        $response = $response->withJson($status);

        return $response;
    }


/*---------------------------------
--------Email Check------------
-----------------------------------*/


    private function emailCheck($email){
      $user=$this->table->where('citizenEmail', "=", $email)->get();
      if($user){
        return true;
      }
      else{
        return false;
      }


    }

    public function authorize($token){
      $citizenDetails=$this->ci->get('db')->table('session')->where('token', '=', $token)->get();

      if($citizenDetails){
        $citizenId=$citizenDetails[0]->citizenId;
        $status=Array('citizenId'=>$citizenId);
      }
      else $status =null;

      return $status;

    }


    public function endSession($token){
      if($this->ci->get('db')->table('session')->where('token', '=', $token)->delete()){
        return true;
      }
      else return false;



    }

    public function isAdmin($citizenId){
      $user=$this->table->where('id', '=', $citizenId)->get();
      if($user[0]->citizenIsAdmin=='true'){
          return true;
        }
        else return false;
    }

    public function isLeader($citizenId){
      $user=$this->table->where('id', '=', $citizenId)->get();
      if($user[0]->citizenIsLeader=='true'){
          return true;
        }
        else return false;
    }

//genearates tokens and creates a session
    private function generateToken($citizenId, $userAgent){
      $token=bin2hex(openssl_random_pseudo_bytes(16));
      $session = new Session;
      $session->setAttribute('citizenId', $citizenId);
      $session->setAttribute('userAgent', $userAgent[0]);
      $session->setAttribute('active', 1);
      $session->setAttribute('token', $token);


      try{
        $status=$session->saveOrFail();

        if($status=='true'){
          return $token;
        }
        else return "-1" ;

      }
      catch(Exception $e){
        return 0;
      }

    }

}

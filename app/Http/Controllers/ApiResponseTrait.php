<?php

namespace App\Http\Controllers;

trait ApiResponseTrait
{

    public $pagination = 1;
   public function apiResponse($data= null,$message = null,$status = null,$authorization=null){

       $array = [
           'data'=>$data,
           'message'=>$message,
           'status'=>$status,
           'authorization'=>$authorization,
       ];

       return response($array,$status);
   }

   public function valdationResponse($error= null,$status = null){

    $array = [
        'error'=>$error,
        'status'=>$status,
    ];
    
    return response($error,$status);
    }



}
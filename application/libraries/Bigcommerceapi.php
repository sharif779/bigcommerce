<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//use Bigcommerce\Api\Client as Bigcommerce;
class Bigcommerceapi {
    
    public function big_commerce_get($url){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "accept: application/json",
          "content-type: application/json",
          "x-auth-client:".X_AUTH_CLIENT,
          "x-auth-token:".X_AUTH_Token
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return array("res"=>false);
        } else {
          return $response;
        }
    }
    public function big_commerce_get_and_store_file($url,$filepath){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "accept: application/json",
          "content-type: application/json",
          "x-auth-client:".X_AUTH_CLIENT,
          "x-auth-token:".X_AUTH_Token
        ),
        ));
        if(!file_exists($filepath)){
            shell_exec("touch ".$filepath);
            shell_exec("chmod 777 ".$filepath);
            
        }
        file_put_contents($filepath,curl_exec($curl));
        //$response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          return array("res"=>false);
        } else {
          return array("res"=>true);
        }
    }
    public function big_commerce_post($url,$json_data){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $json_data,
        CURLOPT_HTTPHEADER => array(
          "accept: application/json",
          "content-type: application/json",
          "x-auth-client:".X_AUTH_CLIENT,
          "x-auth-token:".X_AUTH_Token
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return array("res"=>false);
        } else {
          return $response;
        }
    }
    
}



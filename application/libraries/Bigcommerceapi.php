<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Bigcommerce\Api\Client as Bigcommerce;
class Bigcommerceapi {
    
    public function upload_products_gonzoo($data){
        Bigcommerce::configure(array(
                'store_url' => 'https://store-eu1qmezkwt.mybigcommerce.com',
                'username'	=> '499qpjwvo9g9odjavymc0l4fpp3d7y7',
                'api_key'	=> '8sgxuy7ausj1mcjjekucq29xevbmw0z'
        ));
        $ping = Bigcommerce::getTime();
        return $ping;
    }
    
}



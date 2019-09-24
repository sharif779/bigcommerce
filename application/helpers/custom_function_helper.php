<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('response_format_change'))
{
	/**
	 response	 	 	 
            meta	 	 
 	 	version	The version of the API in use
 	 	code	The HTTP status code of the response. Options are:
                    200 Ok
                    400 Bad Request. The information you passed into the call was missing or incorrect.
                    401 Unauthorised. The API key used does not have permission to perform the action you have requested.
                    404 Not Found. The function cannot be found.
                    500 Internal Server Error. There was an error on the server.
                status	The status of the response. Options are:
                    success
                    failure
                        If the bulk parameter is used in the call then the status of the call will be a success even if all the individual data elements generate errors.

                request	The request path/query string for which this is the response
                time	Unix timestamp for the response
                count	For a successful request, how many records are being returned
                limit	For this request, what was the record limit being applied
            data	 	 
                Successful Requests
                        For successful requests this node will contain the information that is specified to be returned in the documentation for the individual call.
                Unsuccessful Requests
                    error	The type of error. Options:
                        KEY_INVALID The API key is not valid
                        NOTFOUND You have requested an invalid action
                        NOTAUTHORISED You do not have permission to perform that action on the data you have requested
                        DATA_INVALID Some or all of the data you provided with the API call is missing/invalid
                        ERROR An error that doesn't fit into the above categories, you should check the description for more information.
 	 	description	A more detailed description of the error, if available.
	 */
	function response_format_change($code,$data=array(),$error_des=NULL,$http_ok,$limit=NULL)
	{   
                $CI =& get_instance();
		$request_path=$CI->config->base_url($CI->uri->uri_string());
		$meta=array(
                    "version"=>API_VERSION,
                    "code"=>$code,
                    "status"=>"",
                    "request"=>$request_path,
                    "time"=> time(),
                    "count"=> sizeof($data),
                    "limit"=>$limit
                    );
                $error="ERROR";
                switch($code){
                    case 401:
                        $error="KEY_INVALID";
                        break;
                    case 404:
                        $error="NOTFOUND";
                        break;
                    case 403:
                        $error="FORBIDDEN";
                        break;
                    case 400://custom code sent from controller
                        $error="DATA_INVALID";
                        break;
                    deafault:
                         $error="ERROR";
                    
                }
                if(!$http_ok){//that means error occours
                    $data=array(
                        "error"=>$error,
                        "description"=>$error_des
                    );
                    $meta['status']="failure";
                    unset($meta['limit']);
                }else{
                    $meta['status']="success";
                }
                $response=array();
                $response["response"]=array("meta"=>$meta,"data"=>$data);
                return $response;
                
	}
}

//for custom log function 
if ( ! function_exists('log_me'))
{
    function log_me($data,$loc="/tmp/test.log"){
        if(file_exists("/tmp/test.log")){
            error_log(print_r($data,true),3,$loc);
        }
    }
}
//for curl request
if ( ! function_exists('branddistribution_curl_request'))
{
    function branddistribution_curl_request($url,$is_post,$data=""){
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        //for get request
        if(!$is_post){
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic '. base64_encode("82115d12-bc25-47ff-9e8b-72b02e205d21:123Ebay123") // <---
            );
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER=>$headers,
                CURLOPT_URL => $url,
            ]);
        }else{
            $headers = array(
                'Content-Type:application/xml',
                'Authorization: Basic '. base64_encode("82115d12-bc25-47ff-9e8b-72b02e205d21:123Ebay123") // <---
            );
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER=>$headers,
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data
            ]);
        }
        $file_path=FCPATH."resources/products.xlsx";
        if(!file_exists($file_path)){
            shell_exec("touch ".$file_path);
            shell_exec("chmod 777 ".$file_path);
            
        }
        if($is_post){
            $response=curl_exec($curl);
            curl_close($curl);
            return array("res"=>$response);
        }else{
            // Send the request & save response to $resp
            file_put_contents($file_path,curl_exec($curl));
            // Close request to clear up some resources
            curl_close($curl);
            return array("res"=>"sucessfully uploaded to db");
        }
        
        
    }
}
//for curl request
if ( ! function_exists('gonzoo_curl_request'))
{
    function gonzoo_curl_request($url,$is_post,$data=""){
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        //for get request
        if(!$is_post){
            $headers = array(
                'Content-Type:application/json',
                'Authorization: Basic '. base64_encode("82115d12-bc25-47ff-9e8b-72b02e205d21:123Ebay123") // <---
            );
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER=>$headers,
                CURLOPT_URL => $url,
            ]);
        }else{
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $data
            ]);
        }
        if(!file_exists(CSV_FILE_PATH)){
            shell_exec("touch ".CSV_FILE_PATH);
            shell_exec("chmod 777 ".CSV_FILE_PATH);
            
        }
        // Send the request & save response to $resp
        file_put_contents(CSV_FILE_PATH,curl_exec($curl));
        // Close request to clear up some resources
        curl_close($curl);
        return array("res"=>"sucessfully uploaded to db");
    }
}

if ( ! function_exists('isJson'))
{    function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
if ( ! function_exists('xml2array'))
{    
    function xml2array ( $xmlObject, $out = array () )
    {
        foreach ( (array) $xmlObject as $index => $node )
            $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

        return $out;
    }
}


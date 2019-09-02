<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class User extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
       $this->load->helper('custom_function');
       $this->load->model('user_model');
    }
       
    /**
     * Get All Data from this method.
     *filetr if params exists
     * @return Response
    */
    public function index_get($id = -1)
    {       
        $get=$_GET;
        $response_format=isset($get['dataFormat'])?strtolower($get['dataFormat']):"";
        $limit="";
        if(isset($get['apiKey']) && $get['apiKey']==API_KEY ){
            $response= $this->user_model->get_users($get);
            $this->response($response, REST_Controller::HTTP_OK,FALSE,TRUE,"",$limit,$response_format);
        }else{
            $response="Authentication failed";
            $this->response($response, REST_Controller::HTTP_UNAUTHORIZED,FALSE,TRUE,"",$limit,$response_format);
        }
        
    }
    
}
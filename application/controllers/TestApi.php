<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class TestApi extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       //$this->load->database();
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function index_get($id = 0)
    {

        $data=array();
        $data["test"]="Test API";
        $this->response($data, REST_Controller::HTTP_OK);
    }
      
}
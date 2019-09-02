<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class Products extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       $this->load->database();
       $this->load->helper('custom_function');
       $this->load->model('products_model');
    }
       
    /**
     * Get All Data from this method.
     *filetr if params exists
     * @return Response
    */
    public function index_get()
    {       
        $get=$_GET;
        $response_format=isset($get['dataFormat'])?strtolower($get['dataFormat']):"";
        
        $data = [];
        foreach ($csvFile as $line) {
            $data[] = str_getcsv($line);
        }
        $this->response($data, REST_Controller::HTTP_OK);
    }
    public function upload_products_db_get(){
        $url="https://www.brandsdistribution.com/restful/export/api/products.csv";
        $res= branddistribution_curl_request($url, false);
        $this->products_model->upload_csv_into_db();
        $this->response(array("res"=>"sucessfully uploaded to db"), REST_Controller::HTTP_OK);
        
    }
    public function upload_products_gonzoo_get(){
        $i=0;
        while(true){
            $products=$this->products_model->get_branddistribution_data(500,$i);//limit,offset
            foreach($products as $prod){
                
                
            }
            if($i>1000){
                break;
            }
            $i=$i+500;
            
        }
        
        $url="https://www.brandsdistribution.com/restful/export/api/products.csv";
        //$res= curl_request($url, false);
        $this->products_model->upload_csv_into_db();
        $this->response("true", REST_Controller::HTTP_OK);
        
    }
    
}

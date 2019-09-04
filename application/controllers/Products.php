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
       //$this->load->database();
       $this->load->helper('custom_function');
       $this->load->library('bigcommerceapi');
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
    public function upload_categories_db_get(){
        $url="https://www.brandsdistribution.com/restful/export/api/products.csv";
        $res= branddistribution_curl_request($url, false);
        $this->products_model->upload_categories_into_db();
        $this->response(array("res"=>"sucessfully uploaded to db"), REST_Controller::HTTP_OK);
        
    }
    public function send_order_to_branddistribution_get(){
        $url="https://www.brandsdistribution.com/restful/ghost/supplierorder/acquire/";
        $res= branddistribution_curl_request($url, false);
        $this->products_model->upload_csv_into_db();
        $this->response(array("res"=>"sucessfully uploaded to db"), REST_Controller::HTTP_OK);
        
    }
    public function upload_products_gonzoo_get(){
        $i=0;
        $res=$this->bigcommerceapi->upload_products_gonzoo("test");
//        while(true){
//            $products=$this->products_model->get_branddistribution_data(2,$i);//limit,offset
//            foreach($products as $prod){
//                $temp=array();
//                $option_values1=array();
//                $option_values2=array();
//                $categories=array(23,21);
//                $variants=array();
//                $color1=$prod['7dayssale'];
//                $color2=$prod['partner'];
//                if($color1!=$color2){
//                    $option_values1[]=array("option_display_name"=>"Color",'label'=>$color1);
//                    $option_values2[]=array("option_display_name"=>"Color",'label'=>$color2);
//                    $variants_1=array("sku"=>"SKU-". strtoupper($color1),'option_values'=>$option_values1);
//                    $variants_2=array("sku"=>"SKU-". strtoupper($color2),'option_values'=>$option_values2);
//                    $variants[]=$variants_1;
//                    $variants[]=$variants_2;
//                }else{
//                    $option_values1[]=array("option_display_name"=>"Color",'label'=>$color1);
//                    $variants_1=array("sku"=>"SKU-". strtoupper($color1),'option_values'=>$option_values1);
//                    $variants[]=$variants_1;
//                }
//                $temp['name']=$prod['name'];
//                $temp['weight']=$prod['weight'];
//                $temp['type']='physical';
//                $temp['price']=$prod['price_novat'];
//                $temp['categories']=$categories;
//                $temp['variants']=$variants;
//                $temp['Origin Locations']='000002';
//                $res=$temp;
//            }
//            if($i>1000){
//                break;
//            }
//            $i=$i+500;
//            
//        }
        $this->response($res, REST_Controller::HTTP_OK);
        
    }
    
}

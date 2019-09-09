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
        $url="https://api.bigcommerce.com/stores/".STORE."/v3/catalog/categories";
        $return=$this->bigcommerceapi->big_commerce_get($url);
        if(isJson($return)){
            $categories=json_decode($return,true);
            if(isset($categories['data'])){
                $this->products_model->truncate_table("categories");
                foreach($categories['data'] as $cat){
                    $this->products_model->upload_categories_into_db($cat);
                }
            }
            $this->response(array("res"=>"sucessfully uploaded to db"), REST_Controller::HTTP_OK);
        }
    }
    public function send_order_to_branddistribution_get(){
        $url="https://www.brandsdistribution.com/restful/ghost/supplierorder/acquire/";
        $res= branddistribution_curl_request($url, false);
        $this->products_model->upload_csv_into_db();
        $this->response(array("res"=>"sucessfully uploaded to db"), REST_Controller::HTTP_OK);
        
    }
    public function upload_products_gonzoo_get(){
        $i=0;
        $url="https://api.bigcommerce.com/stores/".STORE."/v3/catalog/products";
        $return=array();
        while(true){
            $products=$this->products_model->get_branddistribution_data(2,$i);//limit,offset
            $res=array();
            foreach($products as $prod){
                $temp=array();
                $option_values1=array();
                $option_values2=array();
                $categories=$this->products_model->get_Categorie_id_db($prod['Categorie'],$prod['service'],$prod['Sottocategorie']);
                $variants=array();
                $color1=$prod['7dayssale'];
                $color2=$prod['partner'];
                if($color1!=$color2){
                    $option_values1[]=array("option_display_name"=>"Color",'label'=>$color1);
                    $option_values2[]=array("option_display_name"=>"Color",'label'=>$color2);
                    $variants_1=array("sku"=>"SKU-". strtoupper($color1),'option_values'=>$option_values1);
                    $variants_2=array("sku"=>"SKU-". strtoupper($color2),'option_values'=>$option_values2);
                    $variants[]=$variants_1;
                    $variants[]=$variants_2;
                }else{
                    $option_values1[]=array("option_display_name"=>"Color",'label'=>$color1);
                    $variants_1=array("sku"=>"SKU-". strtoupper($color1),'option_values'=>$option_values1);
                    $variants[]=$variants_1;
                }
                $temp['name']=$prod['name'];
                $temp['weight']=$prod['weight'];
                $temp['type']='physical';
                $temp['price']=$this->convert_price($prod['price_novat'])+$prod['income'];
                $temp['categories']=$categories;
                $temp['variants']=$variants;
                $temp['Origin Locations']='000002';
                $res=$temp;
            }
            if($i>1000){
                break;
            }
            $i=$i+500;
            log_me($res);
            $return=$this->bigcommerceapi->big_commerce_post($url, json_encode($res));
            
        }
        $this->response($return, REST_Controller::HTTP_OK);
        
    }
    
    public function convert_price($price,$price_from="EUR",$price_to="GBP"){
        $url="https://api.bigcommerce.com/stores/".STORE."/v2/currencies";
        $res=$this->bigcommerceapi->big_commerce_get($url);
        $convert_price=$price;
        if(isJson($res)){
            $data= json_decode($res,true);
            foreach ($data as $dta){
                if($dta['currency_code']=$price_to){
                    $convert_price=$price/$dta['currency_exchange_rate'];
                }
            }
            
        }
        return $convert_price;
    }
    
}

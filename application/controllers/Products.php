<?php
   
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/XMLReader.php';
//lets Use the Spout Namespaces
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Common\Type;
use Parser\XMLReader;
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
       $this->load->library('Array2XML');
       $this->load->model('products_model');
       $this->xls_column=array("record_type","product_id","brand","name","code","product_quantity","street_price","suggested_price","price_novat"
           ,"plain_description","weight","picture 1","picture 2","picture 3","madein","Firme","heel","lenght","mainmaterial","Categorie","Produzione","Sottocategorie",
           "Promo","Discount Percentage","season","color","Warehouse2","bicolors","Genere","productname","model_id","barcode","model_size","model_quantity"
           );
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
        //$url="https://www.brandsdistribution.com/restful/export/products.xls?acceptedlocales=en_US&output-filetype=xls";
        //$res= branddistribution_curl_request($url, false);
       //$this->products_model->upload_csv_into_db();
//        $this->upload_xls_into_db();
        $this->upload_xml_into_db();
        $this->response(array("res"=>"sucessfully uploaded to db"), REST_Controller::HTTP_OK);
        
    }
     public function upload_xml_into_db(){
         ini_set('max_execution_time', 300000);
        $xml = new XMLReader(FCPATH. 'resources'); // Download and state files!
        $xml->setCredentials(
            'https://www.brandsdistribution.com/restful/export/api/products.xml', // endpoint
            '82115d12-bc25-47ff-9e8b-72b02e205d21',
            '123Ebay123'
        );
        $xml->download();
        $xml->parse();
        while ($node = $xml->getItem()){
            $node_arr=xml2array($node);
            if(!isset($node_arr['id']) || !isset($node_arr['pictures']['image'])){
                continue;
            }
            $temp=array();
            $temp['record_type']="PRODUCT";
            $temp['product_id']=$node_arr['id'];
            $temp['brand']=$node_arr['brand'];
            $temp['name']=$node_arr['name'];
            $temp['code']=$node_arr['code'];
            $temp['product_quantity']=$node_arr['availability'];
            $temp['street_price']=$node_arr['streetPrice'];
            $temp['suggested_price']=$node_arr['suggestedPrice'];
            $temp['price_novat']=$node_arr['taxable'];
            $temp['weight']=$node_arr['weight'];
            //$temp['plain_description']= strip_tags($node_arr['description']);
            $temp['plain_description']= $node_arr['description'];
            $picture=xml2array($node_arr['pictures']['image']);
            $models=xml2array($node_arr['models']['model']);
            $temp['picture1']=isset($picture[0]['url'])?"https://www.brandsdistribution.com".$picture[0]['url']:"";
            $temp['picture2']=isset($picture[1]['url'])?"https://www.brandsdistribution.com".$picture[1]['url']:"";
            $temp['picture3']=isset($picture[2]['url'])?"https://www.brandsdistribution.com".$picture[2]['url']:"";
            if(is_array($node_arr['madein'])){
                if(isset($node_arr['madein'][0])){
                    $temp['madein']=$node_arr['madein'][0];
                }
            }else{
                $temp['madein']=$node_arr['madein'];
            }
            $tags=xml2array($node_arr['tags']['tag']);
            foreach($tags as $tag){
                if($tag['name']=="category"){
                    $tag['name']="Categorie";
                }
                if($tag['name']=="subcategory"){
                    $tag['name']="Sottocategorie";
                }
                if($tag['name']=="gender"){
                    $tag['name']="service";
                }
                $temp[$tag['name']]=$tag['value']['value'];
            }
            $models_arr= xml2array($node_arr['models']['model']);
            $model_quantity= sizeof($models_arr);
            $temp['model_quantity']=$model_quantity;
            error_log(print_r($models,true),3,"/tmp/test.log");
            error_log(print_r($node_arr,true),3,"/tmp/test.log");
            $this->products_model->upload_xls_into_db($temp);
            $this->products_model->upload_models_into_db($models,$temp['product_id']);
        }

     }
    public function upload_xls_into_db(){
        try {
                
           
               //Lokasi file excel       
               $file_path =FCPATH."resources/products.xlsx";
               $reader = ReaderFactory::create(Type::XLSX); //set Type file xlsx
               $reader->open($file_path); //open the file     
                $i = 0; 
                /**                  
                * Sheets Iterator. Kali aja multiple sheets                  
                **/           
                foreach ($reader->getSheetIterator() as $sheet) {
                    //Rows iterator                
                    foreach ($sheet->getRowIterator() as $row) {
                        $temp=array();
                        if($i==0){
                            $this->xls_column=$row;//for column
                        }else{
                            if(isset($row[0]) && $row[0]="PRODUCT"){
                                foreach($this->xls_column as $key=>$column){
                                    $temp[$column]=$row[$key];
                                }
                                $this->products_model->upload_xls_into_db($temp);
                               // log_me($temp);
                            }//
                        }
                        $i++;
                    }
                  
                }
                //log_me($this->xls_column);
                $reader->close();

      } catch (Exception $e) {

              echo $e->getMessage();
              exit;   
      }
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
        $url="https://api.bigcommerce.com/stores/".STORE."/v2/orders";
        $res=$this->bigcommerceapi->big_commerce_get_and_store_file($url,FCPATH."/resources/orders.json");
        $listener = new \JsonStreamingParser\Listener\InMemoryListener();
        $stream = fopen(FCPATH."resources/orders.json", 'rb');
        try {
            $parser = new \JsonStreamingParser\Parser($stream, $listener);
            $parser->parse();
            fclose($stream);
        } catch (Exception $e) {
            fclose($stream);
            throw $e;
        }
        $send_order=array();
        $send_order['products']['product']=array();
        foreach($listener->getJson() as $orderdata){
            if(!isset($orderdata['products']['url'])){
                continue;
            }
            $order_product=$this->bigcommerceapi->big_commerce_get($orderdata['products']['url']);
            $product_details=json_decode($order_product,true);
            $product_id_arr=array();
            $sku="";
            $quantity=1;
            foreach($product_details as $product_i){
                $product_id_arr[]=$product_i['product_id'];
                $sku=$product_i['sku'];
                $quantity=$product_i['quantity'];
            }
            foreach($product_id_arr as $product_id){
                $meta_url="https://api.bigcommerce.com/stores/".STORE."/v3/catalog/products/$product_id/metafields";
                $meta_data=$this->bigcommerceapi->big_commerce_get($meta_url);
                $meta= json_decode($meta_data,true);
                if(isset($meta['data'])){
                    $is_origin=false;
                    foreach($meta['data'] as $me_dta){
                        if(isset($me_dta['key']) && $me_dta['key']=="shipping-origins" && isset($me_dta['value']) && $me_dta['value']=="000002"){
                            $is_origin=true;
                        }
                    }
                    if($is_origin){
                        $stock_id= str_replace("BD-","", $sku);
                        $send_products=array(
                            '@attributes' => array(
                                'stock_id' => $stock_id,
                                'quantity'=>$quantity
                            )
                        );
                        $send_order['products']['product'][]=$send_products;
                        
                    }
                }
                
            }
        }
        $xml = Array2XML::createXML("supplierorder", $send_order);//
        $order_xml=$xml->saveXML();
        $order_url="https://www.brandsdistribution.com/restful/ghost/supplierorder/acquire/";
        $response=branddistribution_curl_request($order_url, true,$order_xml);
        $this->response(array("res"=>$response), REST_Controller::HTTP_OK);
        
    }
    public function upload_products_gonzoo_get(){
        ini_set('max_execution_time', 300000);
        $i=0;
        $url="https://api.bigcommerce.com/stores/".STORE."/v3/catalog/products";
        $return=array();
        while(true){
            $products=$this->products_model->get_branddistribution_data(500,$i);//limit,offset
            $res=array();
            foreach($products as $prod){
                if($prod['insert_flag']==1 || $prod['record_type']=="MODEL"){
                    continue;
                }
                $temp=array();
                $option_values1=array();
                $option_values2=array();
                $categories=$this->products_model->get_Categorie_id_db($prod['Categorie'],$prod['service'],$prod['Sottocategorie']);
                $variants=array();
                $color_arr=array();
                $size_arr=array();
                $models=$this->products_model->get_products_model_by_id($prod['product_id']);
                foreach($models as $model){
                    if(isset($model['size']) && strtolower($model['size'])!="nosize" && $model['size'] !="" ){
                        if(!in_array($model['size'], $size_arr)){
                            array_push($size_arr,$model['size']);
                        }
                    }
                    if(isset($model['color']) && $model['color'] !="" ){
                        if(!in_array($model['color'], $color_arr)){
                            array_push($color_arr,$model['color']);
                        }
                    }
                }
                //color _variants
                foreach($color_arr as $color){
                    $option_values=array();
                    $option_values[]=array("option_display_name"=>"Color",'label'=>$color);
                    $variants_arr=array("sku"=>'BD-'.$prod['product_id']."-". strtoupper($color),'option_values'=>$option_values);
                    $variants[]=$variants_arr;
                }
//                //size variants
//                foreach($size_arr as $size){
//                    $option_values=array();
//                    $size_sku=preg_replace('/\s+/', '', $size);
//                    $option_values[]=array("option_display_name"=>"Size",'label'=>$size);
//                    $variants_arr=array("sku"=>'BD-'.$prod['product_id']."-". strtoupper($size_sku),'option_values'=>$option_values);
//                    $variants[]=$variants_arr;
//                }
                
                $images=array();
                if(trim($prod['picture1']) !=""){
                    $images[]=array(
                        "image_url"=>$prod['picture1']
                    );
                }
                if(trim($prod['picture2'])!==""){
                    $images[]=array(
			"is_thumbnail"=>true,
                        "image_url"=>$prod['picture2']
                    );
                }
                if(trim($prod['picture3'])!==""){
                    $images[]=array(
                        "image_url"=>$prod['picture3']
                    );
                }
                if(sizeof($images)==0){
                    continue;
                }
                $temp['name']=$prod['name'];
		$temp['description']=$prod['plain_description'];
		$temp['brand_name']=$prod['brand'];
                $temp['weight']=$prod['weight'];
                $temp['type']='physical';
                $temp['sku']='BD-'.$prod['product_id'];
                $temp['price']=$this->convert_price($prod['price_novat'])+$prod['income'];
                $temp['categories']=$categories;
                $temp['images']=$images;
                $temp['variants']=$variants;
                //$temp['Origin Locations']='000002';
		$temp['custom_fields']=array(
			array(
				"name"=>"Origin Locations",
				"value"=>"000002"
			)
		);
                $res=$temp;
                $product_details=$this->bigcommerceapi->big_commerce_post($url, json_encode($temp));
                $product_det= json_decode($product_details,true);
                error_log(print_r($prod['Categorie']."=".$prod['service']."=".$prod['Sottocategorie'],true),3,"/var/log/test.log");
                error_log(print_r($categories,true),3,"/var/log/test.log");
                error_log(print_r($product_det,true),3,"/var/log/test.log");
                error_log(print_r("===================================================================",true),3,"/var/log/test.log");
                if(isset($product_det['data'])){
                    $product_id=$product_det['data']['id'];
                    $this->set_meta_data_branddistro($product_id);
                    $this->send_size_variants_gonzoo($prod['product_id'],$product_id);
                    $this->products_model->sync_insert_flag_db($prod['product_id'],$product_id);
                }
            }
            if(sizeof($products)<499){
                break;
            }
            $i=$i+500;
        }
        $this->response("Successfully product added", REST_Controller::HTTP_OK);
        
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
    
    public function set_meta_data_branddistro($product_id){
        $url="https://api.bigcommerce.com/stores/".STORE."/v3/catalog/products/{$product_id}/metafields";
        $temp=array();
        $temp["permission_set"]="write";
        $temp["key"]="shipping-origins";
        $temp["value"]="000002";
        $temp["namespace"]="shipping.shipperhq";
        $res=$this->bigcommerceapi->big_commerce_post($url, json_encode($temp));
    }
    public function send_size_variants_gonzoo($product_id,$bigcommerce_prod_id){
                
                $models=$this->products_model->get_products_model_by_id($product_id);
               
                $temp=array();
                $temp['product_id']=$product_id;
                $temp['name']="Size Rectangle";
                $temp['display_name']="Size";
                $temp['type']="rectangles";
                $temp['option_values']=array();
                $size_arr=array();
                foreach($models as $model){
                    if(isset($model['size']) && strtolower($model['size'])!="nosize" && $model['size'] !="" ){
                        if(!in_array($model['size'], $size_arr)){
                            array_push($size_arr,$model['size']);
                        }
                    }
                }
                foreach($size_arr as $key=>$size){
                    $option_values_arr=array();
                    $default=false;
                    if($key==0){
                        $default=true;
                    }
                    $option_values_arr['label']=$size;
                    $option_values_arr['sort_order']=$key;
                    $option_values_arr['is_default']=$default;
                    $temp['option_values'][]=$option_values_arr;
                    
                }
                $url="https://api.bigcommerce.com/stores/".STORE."/v3/catalog/products/$bigcommerce_prod_id/options";
                $product_details=$this->bigcommerceapi->big_commerce_post($url, json_encode($temp));
                $product_det= json_decode($product_details,true);
                error_log(print_r($product_det,true),3,"/var/log/test.log");
                if(isset($product_det['data'])){
                    $this->products_model->sync_models_insert_flag_db($product_id);
                }
           
        
        $this->response("Successfully product added", REST_Controller::HTTP_OK);
    }
    public function test_get(){
        $prod['product_id']=103275;
        $variants=array();
        $color_arr=array();
        $size_arr=array();
        $models=$this->products_model->get_products_model_by_id(103275);
        foreach($models as $model){
            if(isset($model['size']) && strtolower($model['size'])!="nosize" && $model['size'] !="" ){
                if(!in_array($model['size'], $size_arr)){
                    array_push($size_arr,$model['size']);
                }
            }
            if(isset($model['color']) && $model['color'] !="" ){
                if(!in_array($model['color'], $color_arr)){
                    array_push($color_arr,$model['color']);
                }
            }
        }
        //color _variants
        foreach($color_arr as $color){
            $option_values=array();
            $option_values[]=array("option_display_name"=>"Color",'label'=>$color);
            $variants_arr=array("sku"=>'BD-'.$prod['product_id']."-". strtoupper($color),'option_values'=>$option_values);
            $variants[]=$variants_arr;
        }
        //size variants
        foreach($size_arr as $size){
            $option_values=array();
            $size_sku=preg_replace('/\s+/', '', $size);
            $option_values[]=array("option_display_name"=>"Size",'label'=>$size);
            $variants_arr=array("sku"=>'BD-'.$prod['product_id']."-". strtoupper($size_sku),'option_values'=>$option_values);
            $variants[]=$variants_arr;
        }
        error_log(print_r($variants,true),3,"/var/log/test.log");
        
    }
    
}
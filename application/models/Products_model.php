<?php
class Products_model extends CI_Model {
    
    public function upload_csv_into_db(){
        $file_path=FCPATH."resources/products.csv";
        if(filesize($file_path)) {
            $this->db->truncate('branddistribution_products');
        }
        $sql="LOAD DATA LOCAL INFILE '".$file_path."' INTO TABLE branddistribution_products FIELDS TERMINATED BY ','ENCLOSED BY '\"'LINES TERMINATED BY '\n' IGNORE 1 LINES";
        $query = $this->db->query($sql);
    }
    public function upload_xls_into_db($data){
        $fields=$this->db->list_fields('branddistribution_products');
        if(isset($data['picture 1'])){
            $data['picture1']=$data['picture 1'];
        }
        if(isset($data['picture 2'])){
            $data['picture1']=$data['picture 2'];
        }
        if(isset($data['picture 3'])){
            $data['picture1']=$data['picture 3'];
        }
        if(isset($data['Discount Percentage'])){
            $data['DiscountPercentage']=$data['Discount Percentage'];
        }
        foreach($data as $key=>$v){
            if(!in_array($key, $fields)){
                unset($data[$key]);
            }
        }
        
        $this->db->where('product_id', $data['product_id']);
        $this->db->from('branddistribution_products');
        $count = $this->db->count_all_results();
        if($count==0){
            $this->db->insert('branddistribution_products', $data);
        }else{
            unset($data['product_id']);
            unset($data['code']);
            $this->db->update('branddistribution_products', $data);
        }
    }
    public function upload_categories_into_db($data){
        if(isset($data['id']) && isset($data['parent_id'])){
            $val['id']=$data['id'];
            $val['parent_id']=$data['parent_id'];
            $val['name']=$data['name'];
            $val['is_visible']=$data['is_visible'];
            $this->db->insert('categories', $val);
        }
        
    }
    public function get_Categorie_id_db($cat,$service,$sottocat){
        $data=array();
        $this->db->like("bigcommerce_cat",$cat);
        if($service=="unisex"){
            $arr = array('men', 'women');
            $this->db->where_in("bigcommerce_service",$arr);
        }else{
            $this->db->where("bigcommerce_service",$service);
        }
        
        //$this->db->like("bigcommerce_Sottocategorie",$sottocat);
        $this->db->select("id");
        $categories=$this->db->get('categories')->result_array();
        foreach($categories as $val){
            if(isset($val['id'])){
                array_push($data,$val['id']);
            }
        }
        if(count($data)==0){
            $data=array(61);
        }
        return $data;
    }
    
    public function get_branddistribution_data($limit,$offset){
        //$this->db->where('insert_flag',0);
        $this->db->limit($limit,$offset);
        $result = $this->db->get('branddistribution_products')->result_array();
        return $result;
    }
    //get count all rows from table
    public function get_count_rows($table){
        $rows=$this->db->from($table)->count_all_results();
        return $rows;
    }
    public function truncate_table($table_name){
        $this->db->truncate($table_name);
    }
    public function sync_insert_flag_db($product_id){
        
        $this->db->set('insert_flag',1);
        $this->db->where("product_id",$product_id);
        $this->db->update('branddistribution_products');
        
    }
}
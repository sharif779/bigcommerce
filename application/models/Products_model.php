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
        $gender=ucfirst($service);
        $category= ucfirst($cat);
        $this->db->where("branddistribution_service",$gender);
        $this->db->where("branddistribution_cat",$category);
        $this->db->like("branddistribution_name",$sottocat);
        $this->db->select("id");
        $categories=$this->db->get('categories')->result_array();
        foreach($categories as $val){
            if(isset($val['id'])){
                array_push($data,$val['id']);
            }
        }
        if(count($data)==0){
            $sott_cat_str= str_replace(' ', '', strtolower($sottocat));
            $sottocat_change=$this->get_german_english_word($sott_cat_str);
            $this->db->where("branddistribution_service",$gender);
            $this->db->where("branddistribution_cat",$category);
            $this->db->like("branddistribution_name",$sottocat_change);
            $this->db->select("id");
            $categories=$this->db->get('categories')->result_array();
            foreach($categories as $val){
                if(isset($val['id'])){
                    array_push($data,$val['id']);
                }
            }
        }
        return array_unique($data);
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
    public function get_german_english_word($key){//one types of hack ....need to quick solved
        $lang_arr=array(
            "pantalone"=>"Trousers",
            "maglia"=>"Sweaters",
            "gilet"=>"Sweaters",
            "zeppa"=>"Wedges",
            "sandali"=>"Sandals",
            "occhialidasole"=>"Sunglasses",
            "camicia"=>"Shirts",
            "stringata"=>"Lace up",
            "cappotto"=>"Coats",
            "giacca"=>"Jackets",
            "decollete"=>"Coats",
            "portafoglio"=>"Wallets",
            "orologio"=>"Watches",
            "stivaletto"=>"Sandals",
            "abiti"=>"Dresses",
            "aspalla"=>"Shoulder bags",
            "gonna"=>"skirts",
            "tracolla"=>"Crossbody Bags",
            "stivale"=>"Boots",
            "scarpebasse"=>"Flat shoes",
            "amano"=>"Handbags",
            "ballerina"=>"Ballet flats",
            "zaini"=>"Shoulder bags",
            "pochette"=>"Clutch bags",
            "cintura"=>"Belts",
            "cravatta"=>"Ties",
            "infraditoeciabatte"=>"Flip Flops",
            "felpa"=>"Sweatshirts",
            "slip"=>"Slip-on",
            "guanti"=>"Gloves",
            "sciarpe"=>"Scarves",
            "culotte"=>"Brief",
            "giaccaclassica"=>"Suits",
            "portadocumenti"=>"Briefcases",
            "daviaggio"=>"Travel bags",
            "impermeabile"=>"Trench coat",
            "occhialedavista"=>"Eyeglasses",
            "canotta"=>"Tank tops",
            "perizoma"=>"G-strings",
            "etichettebagaglio"=>"Baggage labels",
            "abitiuomo"=>"Suits",
            "beautycase"=>"Cases"
        );
        if(isset($lang_arr[$key])){
            return $lang_arr[$key];
        }else{
            return "";
        }
    }
}
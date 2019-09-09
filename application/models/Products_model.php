<?php
class Products_model extends CI_Model {
    
    public function upload_csv_into_db(){
        $sql="LOAD DATA LOCAL INFILE '".CSV_FILE_PATH."' INTO TABLE branddistribution_products FIELDS TERMINATED BY ','ENCLOSED BY '\"'LINES TERMINATED BY '\n' IGNORE 1 LINES";
        $query = $this->db->query($sql);
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
        $this->db->where("bigcommerce_service",$service);
        $this->db->like("bigcommerce_Sottocategorie",$sottocat);
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
        $this->db->where('insert_flag',0);
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
}
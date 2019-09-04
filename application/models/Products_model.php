<?php
class Products_model extends CI_Model {
    public function __construct() {
       $this->load->helper('custom_function');
    }
    
    public function upload_csv_into_db(){
        $sql="LOAD DATA LOCAL INFILE '".CSV_FILE_PATH."' INTO TABLE branddistribution_products FIELDS TERMINATED BY ','ENCLOSED BY '\"'LINES TERMINATED BY '\n' IGNORE 1 LINES";
        $query = $this->db->query($sql);
    }
    public function upload_categories_into_db(){
        $sql="LOAD DATA LOCAL INFILE '".CSV_FILE_PATH."' INTO TABLE branddistribution_products FIELDS TERMINATED BY ','ENCLOSED BY '\"'LINES TERMINATED BY '\n' IGNORE 1 LINES";
        $query = $this->db->query($sql);
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
}
<?php
/**
 * Created by PhpStorm.
 * User: nemesis
 * Date: 02.08.18
 * Time: 18:40
 */

namespace Parser;


class State
{
    const STATE_PATH = "";
    const STATE_FILE = 'state.json';
    const TEMP_PATH = "brands_product";
    const DOWNLOAD_FILE = "brands-download.xml";

    /**
     * @var string
     */
    private $basePath;

    /**
     * @var \stdClass
     */
    private $state;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->generateDirStructure();
        $this->load();
    }

    private function generateDirStructure()
    {
        if(!is_dir($this->basePath. DIRECTORY_SEPARATOR. self::STATE_PATH)){
            try{
                mkdir($this->basePath. DIRECTORY_SEPARATOR. self::STATE_PATH);
            }catch (\Exception $exception){
                die("Permission:". $exception->getMessage());
            }
        }

        if(!is_dir($this->basePath. DIRECTORY_SEPARATOR. self::TEMP_PATH)){
            try{
                mkdir($this->basePath. DIRECTORY_SEPARATOR. self::TEMP_PATH);
            }catch (\Exception $exception){
                die("Permission:". $exception->getMessage());
            }
        }
    }

    protected function getTempFile(){
        return $this->basePath. DIRECTORY_SEPARATOR. self::TEMP_PATH. DIRECTORY_SEPARATOR. self::DOWNLOAD_FILE;
    }

    public function __destruct()
    {
        $this->save();
    }

    public function save()
    {
        if($this->state){
            $file = $this->basePath. DIRECTORY_SEPARATOR. self::STATE_PATH. DIRECTORY_SEPARATOR. self::STATE_FILE;
            $fp = fopen($file, 'w');
            fwrite($fp, json_encode($this->state));
            fclose($fp);
            $this->state = null;
        }
    }

    private function load()
    {
        $file = $this->basePath. DIRECTORY_SEPARATOR. self::STATE_PATH. DIRECTORY_SEPARATOR. self::STATE_FILE;
        if(is_file($file)){
            $fp = fopen($file, 'r');
            $this->state = json_decode(fread($fp, filesize($file)));
            fclose($fp);
        }else{
            $this->state = new \stdClass();
        }
    }

    public function setDownload($value){
        $this->state->download = $value;
    }

    public function getDownload()
    {
        return $this->state->download ?? null;
    }
}
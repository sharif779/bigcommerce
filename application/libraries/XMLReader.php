<?php
/**
 * Created by PhpStorm.
 * User: nemesis
 * Date: 02.08.18
 * Time: 18:26
 */

namespace Parser;
require 'State.php';
use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\build_query;
use function GuzzleHttp\Psr7\stream_for;

class XMLReader extends State
{
    const ROOT_ELM = "page";
    const ITEM_ELM = "item";

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var integer
     */
    private $statusCode;

    /**
     * @var array
     */
    private $attr = [];

    /**
     * @var \DOMDocument
     */
    private $dom;

    /**
     * @var \XMLReader
     */
    private $xml;

    /**
     * XMLReader constructor.
     * @param string $basePath
     */
    public function __construct($basePath)
    {
        parent::__construct($basePath);
        $this->client = new Client();
    }

    public function setCredentials($url, $username, $password)
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
    }

    public function download()
    {
        $param = [
            'acceptedlocales' => 'en_US',
            'output-filetype' => 'xml'
        ];
        if($lastUpdate = $this->getDownload()){
            $param['since'] = $lastUpdate;
        }

        $url = $this->url. '?'. build_query($param);

        $resource = fopen($this->getTempFile(), 'w');
        $stream = stream_for($resource);
        $response = $this->client->get($url, [
            'auth' => [$this->username, $this->password],
            'save_to' => $stream,
            'headers' => [
                'Accept-Encoding' => 'gzip'
            ],
            'decode_content' => 'gzip'
        ]);
        fclose($resource);
        $this->statusCode = $response->getStatusCode();
    }

    public function parse()
    {
        // Open XML
        $this->dom = new \DOMDocument();
        $this->xml = new \XMLReader();
        $this->xml->open($this->getTempFile());

        // Global attributes
        while ($this->xml->read() && $this->xml->name !== self::ROOT_ELM);
        $node = simplexml_import_dom($this->dom->importNode($this->xml->expand(), false));
        foreach ($node->attributes() as $key => $value){
            $this->attr[$key] = $value;
        }

        // Position first item
        while ($this->xml->read() && $this->xml->name !== 'item');
        $this->setDownload((string)$this->attr['lastUpdate']);
    }

    /**
     * @return \SimpleXMLElement|null
     */
    public function getItem()
    {
        $node = null;
        if($this->xml->name === self::ITEM_ELM){
            $node = simplexml_import_dom($this->dom->importNode($this->xml->expand(), true));
            $this->xml->next(self::ITEM_ELM);
        }

        return $node;
    }
}
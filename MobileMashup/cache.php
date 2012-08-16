<?php
/*author:   OuyangYutong
  function: A cache class
*/
  
define("LIMIT", 100);
define("VALIDITE", 600);

require_once("resolution.php");

abstract class Cache {
    private $key;
    private $filename;
    private $cache_manager_file;
    private $content;
    private $write = false;
    protected $SUFFIX = null;
    protected $DIR = null;
    /**
     * Construct function. if cache exists, read it.
     * @param $key The key of the cache object
     */
    public function __construct($url){
        $this->key = md5($url);
        $this->filename = $this->DIR . $this->key . $this->SUFFIX;
        $this->cache_manager_file = $this->DIR . 'Cache.Manager';

        if(!is_dir($this->DIR)){
            mkdir($this->DIR);
        }

        if(file_exists($this->filename)){
            if(filemtime($this->filename) - time() > VALIDITE){
                $this->content = file_get_contents($url);
                $this->write = true;
            }
            else {
                $this->content = file_get_contents($this->filename);
            }
        }
        else {
            $this->content = file_get_contents($url);
            $this->write = true;
        }
    }

    /**
     * Destruct function. write the cache content to file.
     * If cache is too much, then delete some by LRU
     */
    public function __destruct(){
        $cacheManager = $this->initCacheManager();
        //update the position by LRU
        $key = array_search($this->key, $cacheManager);
        if($key !== null ){
            unset($cacheManager[$key]);
        }
        $len = array_push($cacheManager, $this->key);

        //delete some cache by LRU
        if($len > LIMIT){
            $key = array_shift($cacheManager);
            $filename = $this->DIR . $key . $this->SUFFIX;
            if(file_exists($filename)){
                unlink($filename);
            }
        }
        
        file_put_contents($this->cache_manager_file, implode("\t", $cacheManager));
        if($this->write){
            file_put_contents($this->filename, $this->content);
            if($this->SUFFIX === '.jpg'){
                Image::resize($this->filename, 300);
            }
        }
    }

    public function getContent(){
        return $this->content;
    }


    /**
     * Read cache manager from file.
     * if not exists, init it to a new array.
     */
    private function initCacheManager(){
        if(file_exists($this->cache_manager_file)){
            return explode("\t", file_get_contents($this->cache_manager_file));
        }
        else{
            return array();
        }
    }
}

class JsonCache extends Cache {
    public function __construct($url){
        $this->SUFFIX = '.cache';
        $this->DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        parent::__construct($url);
    }
}

class ImageCache extends Cache {
    public function __construct($url){
        $this->SUFFIX = '.jpg';      
        $this->DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        parent::__construct($url);
        
    }
}

class Cache_Tester{
    public function test(){
        $this->test_set_cache();
    }

    private function test_set_cache(){
        $cache = new ImageCache('http://www.baidu.com/img/baidu_sylogo1.gif');
        $cache2 = new JSONCache('http://192.168.7.100/MobileMashup/manage.php');
    }
}
/*
$tester = new Cache_Tester;
$tester->test();
*/

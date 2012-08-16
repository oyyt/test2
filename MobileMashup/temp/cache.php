<?php
define("CACHE_DIR", dirname(__FILE__) . DIRECTORY_SEPARATOR .'cache'.  DIRECTORY_SEPARATOR);
define("CACHE_MANAGER", CACHE_DIR . 'Cache.Manager');
define("LIMIT", 100);

/**
 * A simple cache class.
 * cache content persists in file
 */
class Cache {
    private $key;
    private $filename;
    private $content;
    /**
     * Construct function. if cache exists, read it.
     * @param $key The key of the cache object
     */
    public function __construct($key){
        $this->key = $key;
        $this->filename = CACHE_DIR . $key . '.cache';
        if(!is_dir(CACHE_DIR)){
            mkdir(CACHE_DIR);
        }
        if(file_exists($this->filename)){
            $this->content = file_get_contents($this->filename);
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
            echo $key;
            unset($cacheManager[$key]);
        }
        $len = array_push($cacheManager, $this->key);

        //delete some cache by LRU
        if($len > LIMIT){
            $key = array_shift($cacheManager);
            $filename = CACHE_DIR . $key . '.cache';
            if(file_exists($filename)){
                unlink($filename);
            }
        }
        
        file_put_contents(CACHE_MANAGER, implode("\t", $cacheManager));
        file_put_contents($this->filename, $this->content);
    }

    public function isEmpty(){
        return empty($this->content);
    }

    public function setContent($content){
        $this->content = $content;
    }

    /**
     * Read cache manager from file.
     * if not exists, init it to a new array.
     */
    private function initCacheManager(){
        if(file_exists(CACHE_MANAGER)){
            return explode("\t", file_get_contents(CACHE_MANAGER), true);
        }
        else{
            return array();
        }
    }
}

class Cache_Tester{
    public function test(){
        $this->test_set_cache();
    }

    private function test_set_cache(){
        $cache = new Cache(md5(1));
        if($cache->isEmpty()){
            $cache->setContent("test for set cache");
        }
    }
}

$tester = new Cache_Tester;
$tester->test();

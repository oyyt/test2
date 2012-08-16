<?php
/*author:   OuyangYutong
  function: Provide image service in REST framework
*/
function initParms(){
    list(,$pathinfo) = explode('php/', $_SERVER['REQUEST_URI'], 2);
    if(!empty($pathinfo)){
        $parms = array();
        $path = explode('/', $pathinfo, 2);
        if(count($path) > 1){
        	//trim() 函数从字符串的两端删除空白字符和其他预定义字符
            $parms[trim($path[0])] = trim($path[1]);
        }
        $_GET = array_merge($_GET, $parms);
    }
}

initParms();
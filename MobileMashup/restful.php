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
        	//trim() �������ַ���������ɾ���հ��ַ�������Ԥ�����ַ�
            $parms[trim($path[0])] = trim($path[1]);
        }
        $_GET = array_merge($_GET, $parms);
    }
}

initParms();
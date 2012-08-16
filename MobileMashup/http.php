<?php
/*author:   OuyangYutong
  function: Complete the between Proxy and Cloud Services
*/

function http($url)
{
	//将Url转换为utf-8编码
	$urlString=mb_convert_encoding($url, "UTF-8", "GBK");
	//$r = new HttpRequest($urlString,HttpRequest::METH_GET);
	//$response = $r->send();
	//$result = $r->getResponseBody();
	$result = file_get_contents($url);
	//解析成json格式
	$obj = json_decode($result);
	 return $obj;
}
?>
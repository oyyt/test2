<?php
/*author:   OuyangYutong
  function: Complete the between Proxy and Cloud Services
*/

function http($url)
{
	//��Urlת��Ϊutf-8����
	$urlString=mb_convert_encoding($url, "UTF-8", "GBK");
	//$r = new HttpRequest($urlString,HttpRequest::METH_GET);
	//$response = $r->send();
	//$result = $r->getResponseBody();
	$result = file_get_contents($url);
	//������json��ʽ
	$obj = json_decode($result);
	 return $obj;
}
?>
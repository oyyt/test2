<?php
/*author:   OuyangYutong
  function: Complete the cache about image
*/
  
	require_once("cache.php");
	require_once("restful.php");
	$url = $_GET['img'];
	$obj = new ImageCache($url);
	$contents = $obj->getContent();
	header("Content-Type: image/jpeg");
	echo $contents;
?>
<?php
class Lastfm {

function getInfo($inputValues,$outputNames)
{

 	$input = $inputValues[0];
 // 	var_dump($input);
	$tempObj = $this->searchInfo($input);
	$result = $this->handleInfo($tempObj);
	
	return $result;
}
function searchInfo($name)
{
	$url =  'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist='.$name.'&api_key=b25b959554ed76058ac220b7b2e0a026&format=json';
 	$urlString=mb_convert_encoding($url, "UTF-8", "GBK");
//	$r = new HttpRequest($urlString,HttpRequest::METH_GET);
//	$response = $r->send();
//	$result = $r->getResponseBody();
//	$obj = json_decode($result);
	
  $objCache = new JsonCache($urlString);
  $contents = $objCache->getContent();
  $obj = json_decode($contents);
	
//    echo "<pre>";
//    print_r($obj);
//	echo "</pre>";
	return $obj;
}

//invoke('invoke2','Lastfm','getArtistInfo',array('${temp}'),array('name','smallImage','url','largeImage','megaImage'));
function handleInfo($obj)
{
	//$a = $photo->{'photos'};
	$artist = $obj->{'artist'};
	$name = $artist->{'name'};
	$url = $artist->{'url'};
	$image = $artist->{'image'};
	$smallImage = $image[0]->{'#text'};
	$largeImage = $image[2]->{'#text'};
	$megaImage = $image[4]->{'#text'};
	
	$Cache_smallImage = 'http://192.168.7.103/MobileMashup/CacheImage.php/img/'.$smallImage;
	$Cache_url = 'http://192.168.7.103/MobileMashup/CacheImage.php/img/'.$url;
	$Cache_largeImage = 'http://192.168.7.103/MobileMashup/CacheImage.php/img/'.$largeImage;
	$Cache_megaImage = 'http://192.168.7.103/MobileMashup/CacheImage.php/img/'.$megaImage;
	
	$result[] = array(
	   'name' => $name,
	   'smallImage' => $Cache_smallImage,
	   'url' => $Cache_url,
	   'largeImage' => $Cache_largeImage,
	   'megaImage' => $Cache_megaImage,
	   );
 
	 return $result;
}
}
 


 
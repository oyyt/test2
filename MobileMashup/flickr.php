<?php
/*author:   OuyangYutong
  function: Obtain the info from flickr
*/
require_once("cache.php");
require_once "http.php";


class Flickr{

function getPicture($inputValues,$outputNames)
{

 
 	$radius = $inputValues[3];
 	$lat = $inputValues[1];
 	$searchText = $inputValues[4];

 
 	$lon = $inputValues[2];
 	$number = $inputValues[0];
 	if($radius == "not set" || $lat == "not set" || $lon =="not set")
 	{
 		  
 		$temp = $this->flickr_search($radius, $lat, $searchText, $lon, $number);
 		$result =$this->flickr_getinfo($temp,$lat,$lon);
 	}
 	return $result;

}
	
function flickr_search($radius, $lat, $searchText, $lon, $number)
{
	
	 
	  if ($number == "not set" || $number =="")
	  {
	  	$number = 5;
	  }
	     $url = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=883c48edd8529b750ea92270dc01bc0d&text='.$searchText.'&per_page='.$number.'&format=json&nojsoncallback=1';
       $objCache = new JsonCache($url);
       $contents = $objCache->getContent();
       $obj = json_decode($contents);
    //   var_dump($obj);
       return $obj;
       
      
}

//invoke('invoke3','Flickr','getPhotos',array('not set','not set','${invoke2.name}','not set','not set'),array('longitude','title','thumbnailUrl','latitude'));
function flickr_getinfo($photo,$lat,$lon)
{
    $a = $photo->{'photos'};
    $photo = $a->{'photo'};
    foreach ($photo as $a){
    	  $array_photo[] = $a;
    	}
    	
    	for($i = 0; $i<sizeof($array_photo); $i++){
    	   $title = $array_photo[$i]->{'title'};
    	   $farmid = $array_photo[$i]->{'farm'};
    	   $serverid = $array_photo[$i]->{'server'};
    	   $id = $array_photo[$i]->{'id'};
    	   $secret = $array_photo[$i]->{'secret'};
    	   $owner = $array_photo[$i]->{'owner'};
    	   $thumb_url = "http://farm{$farmid}.static.flickr.com/{$serverid}/{$id}_{$secret}.jpg";
    	   $page_url = "http://www.flickr.com/photos/{$owner}/{$id}";
    	   $imageurl = 'http://api.flickr.com/services/rest/?method=flickr.photos.geo.getLocation&api_key=3df24395c4480be75546067f58295137&photo_id='.$id.'&format=json&nojsoncallback=1';
    	    
    	    $obj = http($imageurl);
    	    $stat = $obj->{'stat'};
    	    if($stat == "fail" && $lat == "" && $lon == "")
    	    {
    	    	$longitude = "No longitude";
    	    	$latitude = "No latitude";
    	    }
    	    else
    	    {
    	    	$longitude = $lon;
    	    	$latitude =$lat;
    	    }
    	    

    	   $Cache_thumbUrl = 'http://192.168.7.103/MobileMashup/CacheImage.php/img/'.$thumb_url;
    	
    	$result[$i] = array(
	   'longitude' => $longitude,
	   'title' => $title,
     'thumbnailUrl' => $Cache_thumbUrl,
	   'latitude' => $latitude,
	   );
    	 }
 
      return $result;
}
}
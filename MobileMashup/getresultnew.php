<?php
/*author:   OuyangYutong
  function: Structure the mashup result in Json form to transfer it to mobile client
  result:   mashup result in Json form
*/
function sendResult($activity,$result)
{
  unset($backResult);
	$size = sizeof($activity);
	$activity2 = $activity;
	$backResult['stat'] = 'ok';
 	$backResult['data'] = array();
 	$maxSize = 0;
	for ($i = 0; $i < $size; $i++)
	{
		    $aa = array_shift($activity2);
		    $tempSize = sizeof($result[$aa]['outputValues']);
		    if ($maxSize < $tempSize)
		    {
		    	     $maxSize = $tempSize;
		    }
	}
	
	for ($i =0; $i < $size; $i++)
	{
		
		$tempActivity = array_shift($activity);
		$type = $result[$tempActivity]['APIType'];
		$id =  $result[$tempActivity]['id'];

         if ($type == "Flickr"){
	 	        $photoSize = sizeof($result[$tempActivity]['outputValues']);
	 	             for ($j= 0; $j < $photoSize; $j++){
	 	             	if ($j < $photoSize){
	 	             	    $backResult['data'][$id][]  =
	 	             	     array (
			                       'id'	 => $j + 1,
			                       'title'  => $result[$tempActivity]['outputValues'][$j]['title'] ,
			                       'latitude' => $result[$tempActivity]['outputValues'][$j]['latitude'] ,
			                       'longitude' => $result[$tempActivity]['outputValues'][$j]['longitude'] ,
			                       'thumbnailUrl' => $result[$tempActivity]['outputValues'][$j]['thumbnailUrl'],
			                       'url' => $result[$tempActivity]['outputValues'][$j]['thumbnailUrl'],
			                       );
			         }
			       else {
			       	$backResult['data'][$id][]  =
	 	             	     array (
			                       'id'	 => $j + 1,
			                       'title' => $result[$tempActivity]['outputValues'][0]['title'] ,
			                       'latitude' => "No valid value!",
			                       'longitude' => "No valid value!",
			                       'mediumUrl' => $result[$tempActivity]['outputValues'][0]['thumbnailUrl'],
			                       'url' => $result[$tempActivity]['outputValues'][0]['thumbnailUrl'],
			                       );
			      }
			    }
		}
         
         
         if ($type == "Lastfm"){
         	$lastfmSize = sizeof($result[$tempActivity]['outputValues']);
	 	        	   $difSize = $maxSize - $lastfmSize +1;
	 	             for ($j= 0; $j < $maxSize; $j++){
	 	             	   if ($j < $lastfmSize)
	 	             	   {
	 	             	    $backResult['data'][$id][]  =
	 	             	     array (
			                      'name'	 => $result[$tempActivity]['outputValues'][$j]['name'],
			                      'smallImage' => $result[$tempActivity]['outputValues'][$j]['smallImage'] ,
			                      'url' => $result[$tempActivity]['outputValues'][$j]['url'],
				                    'largeImage' =>  $result[$tempActivity]['outputValues'][$j]['largeImage'],
				                    'megaImage' => $result[$tempActivity]['outputValues'][$j]['megaImage'],
			                       );
			             }
			             else
			             {
			             	    $backResult['data'][$id][]  =
	 	             	     array (
			                       'name'	 => $result[$tempActivity]['outputValues'][0]['name'],
			                       'smallImage' => $result[$tempActivity]['outputValues'][0]['smallImage'] ,
			                       'url' => $result[$tempActivity]['outputValues'][0]['url'],
				                     'largeImage' => $result[$tempActivity]['outputValues'][0]['largeImage'],
				                     'megaImage' => $result[$tempActivity]['outputValues'][0]['megaImage'],
			                       );
			             }
			         }
	        }
	        
	        if ($type == "GoogleStaticMap")
	        {
	        	$googleSize = sizeof($result[$tempActivity]['outputValues']);
	        	for ($j= 0; $j < $maxSize; $j++){
	 	             	    $backResult['data'][$id][]  =
	 	             	     array (
	 	             	           'url' => $result[$tempActivity]['outputValues']['url'],
			                       );
			         }
	        }
	        
	        if ($type == "LyricWiki")
	        { 
	    
	        	$lyricSize = sizeof($result[$tempActivity]['outputValues']);
	        	for ($j= 0; $j < $maxSize; $j++){
	 	             	    $backResult['data'][$id][]  =
	 	             	     array (
	 	             	            'artistO' => $result[$tempActivity]['outputValues'][$j]['artistO'],
	 	             	            'songO' => $result[$tempActivity]['outputValues'][$j]['songO'],
	 	             	            'lyrics' => $result[$tempActivity]['outputValues'][$j]['lyrics'],
	 	             	            'url' => $result[$tempActivity]['outputValues'][$j]['url'],
			                       );
			         }
	        }
}
return $backResult;
}

 
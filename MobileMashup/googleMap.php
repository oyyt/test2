<?php 
/*author:   OuyangYutong
  function: Obtain the info from Google Maps
*/

class GoogleStaticMap
{
	function getInfo($inputValues, $outputValues)
	{
		$lat = $inputValues[1];
		$lon = $inputValues[0];
	 
		$url = 'http://maps.google.com/maps/api/staticmap?size=325x325&maptype=roadmap&markers=color:green|40.70214,-74.012318&sensor=false&zoom=12';
    $Cache_url = 'http://192.168.7.103/MobileMashup/CacheImage.php/img/'.$url;
		$result['url'] = $Cache_url;
		return $result;
	}	
}
?>


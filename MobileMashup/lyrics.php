<?php
/*author:   OuyangYutong
  function: Obtain the info from LyricWiki
*/

class LyricWiki{
	function getInfo($inputValues,$outputNames)
	{
 	$song = $inputValues[0];
 	$artist = $inputValues[1];
 	$result = $this->explain($song, $artist);
 	return $result;
	 
	}
	function explain($song, $artist)
	{
		   $url = 'http://lyricwiki.org/api.php?func=getSong&artist='.$artist.'&song='.$song.'&fmt=realjson';
		   $objCache = new JsonCache($url);
       $contents = $objCache->getContent();
       $obj = json_decode($contents);     
       $artist = $artist;
       $song = $obj->{'song'};
       $lyrics = $obj->{'lyrics'};
       $url = $obj->{'url'};
              
       $result[] = array(
        'artistO' => $artist,
        'songO' => $song,
        'lyrics' => $lyrics,
	      'url' => $url,
	     );
      return $result;
      
	}
}

  
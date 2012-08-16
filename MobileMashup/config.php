<?php
class  mashup
{
function init()
{
getTerminalInput('temp','cher');
addActivity('getTerminalInput');
invoke('invoke2','Lastfm','getArtistInfo',array('${temp}'),array('url','largeImage','name','summary','megaImage'));
addActivity('invoke2');
invoke('invoke3','Flickr','getPhotos',array('not set','not set','not set','not set','${invoke2.name}'),array('latitude','thumbnailUrl','longitude','title'));
addActivity('invoke3');
}
}
?>

<?php
/*author:   OuyangYutong
  function: Mashup Execute Engine
  result:   Generate Mashup result
*/

include_once "config.php";
require_once "lastfm.php";
require_once "flickr.php";
require_once  "googleMap.php";
require_once  "lyrics.php";
function mashupExecute()
{
	
	global $globalArray;
	global $globalActivity;
	
	mashupInit();
	$size = sizeof($globalActivity);
	$resultActivity   = $globalActivity;
	for ($i = 0 ; $i < $size; $i++)
	{
		$currentActivity = array_shift($globalActivity);
		run($currentActivity);
	}	
	require_once "getresultnew.php";
	$sendValue = sendResult($resultActivity,$globalArray);
	return $sendValue;
	unset($globalArray);
	unset($globalActivity);
}

function mashupInit()
{
	$mashupExecute = new mashup;
	$mashupExecute->init();
	global $currentActivity;
	global $globalActivity;
}

function run( $currentActivity)
{
	if (strstr($currentActivity,"invoke")!=false)
	{ 	
		executeInvoke($currentActivity);
	}	
}


function executeInvoke($executeActivity)
{
	global $globalArray;
	global $globalResult;

  $tempId = $globalArray[$executeActivity]['id'];
	$tempAPIType = $globalArray[$executeActivity]['APIType'];
	$tempOperation = $globalArray[$executeActivity]['operation'];
	$tempInputValues = $globalArray[$executeActivity]['inputValues'];
	$tempOutputNames = $globalArray[$executeActivity]['outputNames'];
	$action = new $tempAPIType();

 if  (strstr($tempId,"invoke")!=false)
	{

    $inputSize = sizeof($tempInputValues);
    for ($i = 0; $i < $inputSize; $i++)
    {
       $count = 0;
    	   $temp = $tempInputValues[$i];
    	   unset($tempInputValues[$i]);
    	   unset ($tempnew);
      
    	  for ($j = 0; $j < strlen($temp);$j++)
    	  { 
   
    	  	if ($temp [$j]!="{" && $temp[$j]!="}"&&$temp[$j]!="$")
    	  	{
    	  		$tempnew[$count]  = $temp[$j];
    	  		$count ++;
    	  	}
    	 }
    	
	      global $userInputValue;
	      global $userInputName;
       
	     $tempInputValues[$i] = $tempnew;
       $tempInputValues[$i] =  implode("",$tempInputValues[$i] );
       $inputKey = $tempInputValues[$i];
       
       if(in_array($inputKey, $userInputName)){
        	unset($tempInputValues[$i]);
        	$tempInputValues[$i] = $userInputValue[$inputKey];
       }
         
       //判断该服务调用的输入是否来自其他服务的输出
       if (strstr($tempInputValues[$i],"invoke")!=false)
       {
        
       	$temp = split('\.',$tempInputValues[$i]);
       	$findId = $temp[0];
       	$findName = $temp[1];
       	unset($tempInputValues[$i]) ;
	    $tempInputValues[$i] = assign($findId,$findName);
       }
        
    }
 
  }
  
    //调用具体的服务
	if ($tempOperation == "getArtistInfo")
	{
		$globalArray[$executeActivity]['outputValues']= $action->getInfo($tempInputValues,$tempOutputNames); 
	}
	else if ($tempOperation == "getPhotos")
	{
	    $globalArray[$executeActivity]['outputValues']= $action->getPicture($tempInputValues,$tempOutputNames); 
	}
	else if ($tempOperation == "addMarkers")
	{
		$globalArray[$executeActivity]['outputValues']= $action->getInfo($tempInputValues,$tempOutputNames); 
	}
	else if ($tempOperation == "getLyric")
	{
		$globalArray[$executeActivity]['outputValues']= $action->getInfo($tempInputValues,$tempOutputNames); 
	}

}

function assign($activity,$key)
{
	global $globalArray;
	global $globalResult;
	$result = $globalArray[$activity]['outputValues'][0][$key];
 	return $result;
}
//invoke('invoke3','Flickr','getPhotos',array('not set','not set','${invoke2.name}','not set','not set'),array('longitude','title','thumbnailUrl','latitude'));

function invoke($id, $APIType, $operation,$inputValues, $outputNames)
{
	global $globalArray;
	$globalArray[$id]['id']= $id;
	$globalArray[$id]['APIType']= $APIType;
	$globalArray[$id]['operation'] = $operation;
	$globalArray[$id]['outputNames'] = $outputNames;
	$globalArray[$id]['inputValues'] = $inputValues;
}

//getTerminalInput('temp','Michael Jackson');
function getTerminalInput($tempName, $tempValue)
{
  global $userInputValue;
  global $userInputName;
  $userInputName[] = $tempName;
	$userInputValue[$tempName] = $tempValue;
	 

}

function addActivity($activity)
{
	global $globalActivity;
	$globalActivity[] = $activity;
}

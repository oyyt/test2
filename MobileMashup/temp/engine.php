<?php

require_once "douban.php";
require_once "flickr.php";

function mashupExecute()
{
	global $globalArray;
	global $globalActivity;
	mashupInit();
	$size = sizeof($globalActivity);
	for ($i = 0 ; $i < $size; $i++)
	{
		$currentActivity = array_shift($globalActivity);
		run($currentActivity);
	}	
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
/*	
	if  (strstr($tempInputValues,"invoke")!=false)
	{
	//	$tempInputValues = $globalArray[$tempInputValues]['outputValues'];
	//	$globalArray[$executeActivity]['inputValues'] = $tempInputValues;
	   
	    $global[$executeActivity] = assign($tempInputValues);
	    $tempInputValues = $global[$executeActivity];
	}
*/
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
    	  global $userInputName;
	  global $uerInputValue;

	  $tempInputValues[$i] = $tempnew;
       $tempInputValues[$i] =  implode("",$tempInputValues[$i] );
        
       if ($userInputName == $tempInputValues[$i])
       { 
       	    unset($tempInputValues[$i]);
          	$tempInputValues[$i] = $uerInputValue;
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
		$globalArray[$executeActivity]['outputValues']= $action->getMovieInfo($tempInputValues,$tempOutputNames); 

	}
	else if ($tempOperation == "getPhotos")
	{
	    $globalArray[$executeActivity]['outputValues']= $action->getPicture($tempInputValues,$tempOutputNames); 
	}

}

function assign($activity,$key)
{
	global $globalArray;
	global $globalResult;
//	echo $key;
	$result = $globalArray[$activity]['outputValues'][$key];
 	//var_dump($result);
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
	global $userInputName;
	global $uerInputValue;
	$userInputName = $tempName;
	$uerInputValue = $tempValue;
}

function addActivity($activity)
{
	global $globalActivity;
	$globalActivity[] = $activity;
	}


?>

<?php
/*author:   OuyangYutong
  function: Compile the XML document
  result:   Produce a config document called config.php
*/
  
function compileExecute($getTerminalKey,$getTerminalvalue)
{
start();
compile($getTerminalKey,$getTerminalvalue);
stop();
}

function start()
{
$fp = fopen("config.php","w");
if ($fp)
{
	fwrite($fp, "<?php");
	fwrite($fp, "\r\n");
	fwrite($fp, "class  mashup");
	fwrite($fp, "\r\n");
	fwrite($fp, "{");
	fwrite($fp, "\r\n");
	fwrite($fp, "function init()");
	fwrite($fp, "\r\n");
	fwrite($fp, "{");
	fwrite($fp, "\r\n");
}
else 
{
	echo "Failed to create config.php";
}
}

function stop()
{
$fp = fopen("config.php","a");
fwrite($fp, "}");
fwrite($fp, "\r\n");
fwrite($fp, "}");
fwrite($fp, "\r\n");
fwrite($fp, "?>");
fwrite($fp, "\r\n");
}

function compile($getTerminalKey,$getTerminalvalue)
{

$key = $getTerminalKey[0];
global $filed;
if (!file_exists("xml.xml"))
{
	exit("XML does not exist!");
}
$xml = simplexml_load_file('xml.xml');
foreach($xml->process->children() as $filed)
{
	$filed = (array)$filed;
	$size = sizeof ($filed['@attributes']);
	if ($size == 5)
	{
		compileGetermianlInput($filed,$getTerminalKey,$getTerminalvalue);
	 
	}
	else if ($size == 3)
	{
		compileInvoke($filed);
	}
}
}

function compileGetermianlInput($filed,$getTerminalKey,$getTerminalvalue)
{   
	global $getTerminalValue;
	$fp = fopen("config.php","a");
	$tempName  = $filed['@attributes']['name'];
	$tempLabel = $filed['@attributes']['label'];
	$tempType = $filed['@attributes']['type'];
	$tempControl = $filed['@attributes']['control'];
	$tempValue = $filed['@attributes']['value'];
  if($getTerminalvalue[$tempName] != "")
  {
  	$tempValue = $getTerminalvalue[$tempName];
  }
 
  
	fwrite($fp, "getTerminalInput("."'".$tempName."'".","."'".$tempValue."'".")".";");
	fwrite($fp, "\r\n");
	fwrite($fp, "addActivity("."'"."getTerminalInput"."'".")".";");
    fwrite($fp, "\r\n");
}
function compileInvoke($filed)
{
	
	$fp = fopen("config.php","a");
	$tempId = $filed['@attributes']['id'];
	$tempPortType = $filed['@attributes']['portType'];
	$tempOperation = $filed['@attributes']['operation'];
	
	$inputVariables = (array) $filed['inputVariables'];
	$temp = (array) $inputVariables['inputVariable'];
	
	$outputVariables = (array) $filed['outputVariables'];
	$tempout =  $outputVariables['outputVariable'];
	
	
	 
	$inputSize = sizeof($temp);
	$outputSize = sizeof($tempout);
	
	//判断invoke的输入参数，单一变量和多变量的树形结构不同，需要分开处理
	if ($inputSize == 1)
	{
		$inputArray['name'] = $temp['@attributes']['name'];
		$inputArray['type'] = $temp['@attributes']['type'];
		$inputArray['value'] = $temp['@attributes']['value'];
		$inputArray['defaultValue'] = $temp['@attributes']['defaultValues'];
		$fp = fopen("config.php","a");
		// invoke('invoke3','Flickr,getPhotos',array('not set','not set','${invoke2.name}','not set','not set'),array('longitude','title','thumbnailUrl','latitude'));
		fwrite($fp,"invoke("."'".$tempId."'".","."'".$tempPortType."'".","."'".$tempOperation."'".","."array("."'".$inputArray['value']."')".","."array(");
	}
	else
	{
		for ($i =0 ;$i < $inputSize; $i++)
		{
			$tempInvoke = (array)$temp[$i];

			$mark = $tempInvoke['@attributes']['name'];
			$markInputShow[] = $mark;
			$inputArray[$mark]['name'] = $tempInvoke['@attributes']['name'];
			$inputArray[$mark]['type']   = $tempInvoke ['@attributes']['type'];
			$inputArray[$mark]['value']   = $tempInvoke['@attributes']['value'];
			$inputArray[$mark]['defaultValue']   = $tempInvoke ['@attributes']['defaultValue'];
		
	    }
	
	$markInputSize = sizeof($markInputShow);
	$fp = fopen("config.php","a");
	fwrite($fp,"invoke("."'".$tempId."'".","."'".$tempPortType."'".","."'".$tempOperation."'".","."array(");
	for ($i = 0; $i < $markInputSize; $i++)
	{
		$inputMark = $markInputShow[$i];
		if (($inputArray[$inputMark]['value']) == NULL)
		{
			fwrite($fp,"'".$inputArray[$inputMark]['defaultValue']."'");
			if ($i!=($markInputSize-1))
			fwrite($fp,",");
		}
		else
		{
		fwrite($fp,"'".$inputArray[$inputMark]['value']."'");
		if ($i!=($markInputSize-1))
			fwrite($fp,",");
	   }
	}
	fwrite($fp,")".","."array(");
}

// 输出部分的解析和生成输出的配置文件
    for ($i =0 ;$i < $outputSize; $i++)
		{
			$tempInvoke2 = (array)$tempout[$i];
			$mark = $tempInvoke2['@attributes']['name'];
			$markOutputShow[] = $mark;
			$outputArray[$mark]['name']  = $tempInvoke2['@attributes']['name'];
			$outputArray[$mark]['type']   = $tempInvoke2 ['@attributes']['type'];
	
	    }
	$markOutputSize = sizeof($markOutputShow);
	for ($i = 0; $i < $markOutputSize; $i++)
	{
		$outputMark = $markOutputShow[$i];
		fwrite($fp,"'".$outputArray[$outputMark]['name']."'");
		if ($i!=($markOutputSize-1))
			fwrite($fp,",");
		
	}
	fwrite($fp,")".")".";");
	fwrite($fp, "\r\n");
    fwrite($fp, "addActivity("."'".$tempId."'".")".";");
    fwrite($fp, "\r\n");
}
 
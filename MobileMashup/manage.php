<?php

/*author:   OuyangYutong
  function: The main function
*/
  
require_once "compile.php";
require_once "mashupExecute.php";
require_once "check.php";

foreach($_GET as $key=>$value){
	if ($key != "invokeid")
	{
		$getTerminalKey[] = $key;
		$getTerminalValue[$key] = $value;
	}
}

//check the XML document
$checkResult = checkXML();
if ($checkResult == 1)
 exit("XML document produce errors!");

//compile the XML document
compileExecute($getTerminalKey,$getTerminalValue);

$tempResult = mashupExecute();
$json = json_encode($tempResult);
echo $json;

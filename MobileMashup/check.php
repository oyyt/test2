<?php
/*author:   OuyangYutong
  function: Check XML document
  result:   if the XML document is valid then return 0;
            else return 1;
*/
function checkXml()
{
	 
  $result = 0;
	$xml = new DOMDocument(); 
	$xml->load('./xml.xml');
 
	if (!$xml->schemaValidate('./check.xsd')) 
	{
		$result = 1;
		return $result;
	}
  
	 
}
?>
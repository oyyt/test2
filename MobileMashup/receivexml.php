<?php
/*author:   OuyangYutong
  function: receive XML document
*/

if($_POST){
	$temp1 = urldecode($_POST['xml']);
	if (get_magic_quotes_gpc()==1)
	{		 
		$temp2 = stripcslashes($temp1);     
	}
	else
	{
		$temp2 = $temp1;
	}
	file_put_contents('xml.xml',$temp2);
	echo "OK";	
}
else{
	echo "Send XML failed!";
}
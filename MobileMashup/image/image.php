<?php 
/*   
  *   图片缩略图   
  *   $srcfile   来源图片， 
  *   $rate   缩放比,默认为缩小一半,或者具体宽度象素值 
  *   $filename   输出图片文件名jpg 
  *   例如:   resizeimage( "zt32.gif ",0.1); 
  *   例如:   resizeimage( "zt32.gif ",250　); 
  *   说明:调用时直接把函数的结果放在HTML文件IMG标签中的SRC属性里 
  */ 

 
function   resizeimage($srcfile,$rate=.5,   $filename   =   ""   ){ 
$size=getimagesize($srcfile); 
switch($size[2]){ 
case   1: 
$img=imagecreatefromgif($srcfile); 
break; 
case   2: 
$img=imagecreatefromjpeg($srcfile); 
break; 
case   3: 
$img=imagecreatefrompng($srcfile); 
break; 
default: 
exit; 
} 
//源图片的宽度和高度 
$srcw=imagesx($img); 
$srch=imagesy($img); 
//目的图片的宽度和高度 
if($size[0]   <=   $rate   ||   $size[1]   <=   $rate){ 
$dstw=$srcw; 
$dsth=$srch; 
}else{ 
if($rate   <=   1){ 
$dstw=floor($srcw*$rate); 
$dsth=floor($srch*$rate); 
}else   { 
$dstw=$rate; 
$rate   =   $rate/$srcw; 
$dsth=floor($srch*$rate); 
} 
} 
//echo   "$dstw,$dsth,$srcw,$srch   "; 
//新建一个真彩色图像 
$im=imagecreatetruecolor($dstw,$dsth); 
$black=imagecolorallocate($im,255,255,255); 

imagefilledrectangle($im,0,0,$dstw,$dsth,$black); 
imagecopyresized($im,$img,0,0,0,0,$dstw,$dsth,$srcw,$srch); 
//   以   JPEG   格式将图像输出到浏览器或文件 
if(   $filename   )   { 
    //图片保存输出 
    imagejpeg($im,   $filename   ); 
}else   { 
    //图片输出到浏览器 
    imagejpeg($im); 
} 
//释放图片 
imagedestroy($im); 
imagedestroy($img); 
} 

?>
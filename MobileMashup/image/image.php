<?php 
/*   
  *   ͼƬ����ͼ   
  *   $srcfile   ��ԴͼƬ�� 
  *   $rate   ���ű�,Ĭ��Ϊ��Сһ��,���߾���������ֵ 
  *   $filename   ���ͼƬ�ļ���jpg 
  *   ����:   resizeimage( "zt32.gif ",0.1); 
  *   ����:   resizeimage( "zt32.gif ",250��); 
  *   ˵��:����ʱֱ�ӰѺ����Ľ������HTML�ļ�IMG��ǩ�е�SRC������ 
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
//ԴͼƬ�Ŀ�Ⱥ͸߶� 
$srcw=imagesx($img); 
$srch=imagesy($img); 
//Ŀ��ͼƬ�Ŀ�Ⱥ͸߶� 
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
//�½�һ�����ɫͼ�� 
$im=imagecreatetruecolor($dstw,$dsth); 
$black=imagecolorallocate($im,255,255,255); 

imagefilledrectangle($im,0,0,$dstw,$dsth,$black); 
imagecopyresized($im,$img,0,0,0,0,$dstw,$dsth,$srcw,$srch); 
//   ��   JPEG   ��ʽ��ͼ���������������ļ� 
if(   $filename   )   { 
    //ͼƬ������� 
    imagejpeg($im,   $filename   ); 
}else   { 
    //ͼƬ���������� 
    imagejpeg($im); 
} 
//�ͷ�ͼƬ 
imagedestroy($im); 
imagedestroy($img); 
} 

?>


<?php
/*
ͼƬ�����������ܣ����š����С����ˮӡ���񻯡���ת����ת��͸���ȡ���ɫ
������������ʷ��¼��˼·������ͼƬ�иĶ�ʱ�Զ�����һ����ͼƬ��������ʽ���Կ�����ԭͼƬ�Ļ����ϼ��ϲ��裬���磺ͼƬ����+__�ڼ���

*/
class picture{
var $PICTURE_URL;//Ҫ������ͼƬ
var $DEST_URL="temp__01.jpg";//����Ŀ��ͼƬλ��
var $PICTURE_CREATE;//Ҫ������ͼƬ
var $TURE_COLOR;//�½�һ�����ͼ��

var $PICTURE_WIDTH;//ԭͼƬ����
var $PICTURE_HEIGHT;//ԭͼƬ�߶�

/*
ˮӡ�����ͣ�Ĭ�ϵ�Ϊˮӡ����
*/
var $MARK_TYPE=1;
var $WORD;//����UTF-8�������
var $WORD_X;//���ֺ�����
var $WORD_Y;//����������
var $FONT_TYPE;//��������
var $FONT_SIZE="12";//�����С
var $FONT_WORD;//����
var $ANGLE=0;//���ֵĽǶȣ�Ĭ��Ϊ0
var $FONT_COLOR="#ffffff";//������ɫ
var $FONT_PATH="22.ttf";//����⣬Ĭ��Ϊ����


var $FORCE_URL;//ˮӡͼƬ
var $FORCE_X=0;//ˮӡ������
var $FORCE_Y=0;//ˮӡ������
var $FORCE_START_X=0;//����ˮӡ��ͼƬ������
var $FORCE_START_Y=0;//����ˮӡ��ͼƬ������

var $PICTURE_TYPE;//ͼƬ����
var $PICTURE_MIME;//�����ͷ��

/*
���ű���Ϊ1�Ļ��Ͱ����Ÿ߶ȺͿ�������
*/
var $ZOOM=1;//��������
var $ZOOM_MULTIPLE;//���ű���
var $ZOOM_WIDTH;//���ſ���
var $ZOOM_HEIGHT;//���Ÿ߶�

/*
���У��������͹̶����ȡ�����
*/
var $CUT_TYPE=1;//��������
var $CUT_X=0;//���еĺ�����
var $CUT_Y=0;//���е�������
var $CUT_WIDTH=100;//���еĿ���
var $CUT_HEIGHT=100;//���еĸ߶�

/*
��
*/
var $SHARP="5.0";//�񻯳̶�

/*
͸���ȴ���
*/
var $ALPHA='100';//͸������0-127֮��
var $ALPHA_X="90";
var $ALPHA_Y="50";

/*
����Ƕ���ת
*/
var $CIRCUMROTATE="90.0";//ע�⣬����Ϊ������

/*
������Ϣ
*/
var $ERROR=array(
'unalviable'=>'û���ҵ����ͼƬ!'
);

/*
���캯����������ʼ��
*/
function __construct($PICTURE_URL){

$this->get_info($PICTURE_URL);

}
function get_info($PICTURE_URL){
/*
����ԭͼƬ����Ϣ,�ȼ��ͼƬ�Ƿ����,�������������Ӧ����Ϣ
*/
@$SIZE=getimagesize($PICTURE_URL);
if(!$SIZE){
   exit($this->ERROR['unalviable']);
}

//�õ�ԭͼƬ����Ϣ���͡����ȡ��߶�
$this->PICTURE_MIME=$SIZE['mime'];
$this->PICTURE_WIDTH=$SIZE[0];
$this->PICTURE_HEIGHT=$SIZE[1];

//����ͼƬ
switch($SIZE[2]){
   case 1:
    $this->PICTURE_CREATE=imagecreatefromgif($PICTURE_URL);
    $this->PICTURE_TYPE="imagejpeg";
    $this->PICTURE_EXT="jpg";
    break;
   case 2:
    $this->PICTURE_CREATE=imagecreatefromjpeg($PICTURE_URL);
    $this->PICTURE_TYPE="imagegif";
    $this->PICTURE_EXT="gif";
    break;
   case 3:
    $this->PICTURE_CREATE=imagecreatefrompng($PICTURE_URL);
    $this->PICTURE_TYPE="imagepng";
    $this->PICTURE_EXT="png";
    break;
}

/*
������ɫת��16����ת����10����
*/
preg_match_all("/([0-f]){2,2}/i",$this->FONT_COLOR,$MATCHES);
if(count($MATCHES)==3){
   $this->RED=hexdec($MATCHES[0][0]);
   $this->GREEN=hexdec($MATCHES[0][1]);
   $this->BLUE=hexdec($MATCHES[0][2]);
}
}

#end of __construct

/*
��16���Ƶ���ɫת����10���Ƶģ�R��G��B��
*/
function hex2dec(){
   preg_match_all("/([0-f]{2,2})/i",$this->FONT_COLOR,$MATCHES);
if(count($MATCHES[0])==3){
   $this->RED=hexdec($MATCHES[0][0]);
   $this->GREEN=hexdec($MATCHES[0][1]);
   $this->BLUE=hexdec($MATCHES[0][2]);
}else{
exit('�������ɫ��ʽ');
}
}

//��������
function zoom_type($ZOOM_TYPE){
$this->ZOOM=$ZOOM_TYPE;
}

//��ͼƬ��������,�����ָ���߶ȺͿ��Ⱦͽ�������
function zoom(){
//���ŵĴ�С
if($this->ZOOM==0){
   $this->ZOOM_WIDTH=$this->PICTURE_WIDTH * $this->ZOOM_MULTIPLE;
   $this->ZOOM_HEIGHT=$this->PICTURE_HEIGHT * $this->ZOOM_MULTIPLE;
}
//�½�һ�����ͼ��
$this->TRUE_COLOR=imagecreatetruecolor($this->ZOOM_WIDTH,$this->ZOOM_HEIGHT);
$WHITE=imagecolorallocate($this->TRUE_COLOR,255,255,255);
imagefilledrectangle($this->TRUE_COLOR,0,0,$this->ZOOM_WIDTH,$this->ZOOM_HEIGHT,$WHITE);
imagecopyresized($this->TRUE_COLOR,$this->PICTURE_CREATE,0,0,0,0,$this->ZOOM_WIDTH,$this->ZOOM_HEIGHT,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
}

#end of zoom
//����ͼƬ,��������Զ�
function cut($zoom=0){
$this->TRUE_COLOR=imagecreatetruecolor($this->CUT_WIDTH,$this->CUT_WIDTH);
if(!$zoom){
   imagecopy($this->TRUE_COLOR,$this->PICTURE_CREATE, 0, 0, $this->CUT_X, $this->CUT_Y,$this->CUT_WIDTH,$this->CUT_HEIGHT);
   }else{
   $w=$this->PICTURE_WIDTH;
   $h=$this->PICTURE_HEIGHT;
   if(min($w,$h,$this->CUT_WIDTH,$this->CUT_HEIGHT)==0)exit('�ü��ߴ�Ϊ�㣬���߻�ȡͼƬ�ߴ�');
   $bl=$this->CUT_WIDTH/$this->CUT_HEIGHT;
   $bl1=$w/$h;
   if($bl>$bl1){
    $h=floor($w*$bl);
   }elseif($bl<$bl1){
    $w=floor($h/$bl);
   }
   imagecopyresampled($this->TRUE_COLOR,$this->PICTURE_CREATE,0, 0,$this->CUT_X, $this->CUT_Y,$this->CUT_WIDTH,$this->CUT_HEIGHT,$w, $h);
   }
}

#end of cut
/*
��ͼƬ�Ϸ����ֻ�ͼƬ
ˮӡ����
*/
function _mark_text(){
   $this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
//$this->WORD=mb_convert_encoding($this->FONT_WORD,'utf-8','gb2312');
$this->WORD=iconv('gb2312','utf-8',$this->FONT_WORD);
//$this->WORD=$this->FONT_WORD;
/*
ȡ��ʹ�� TrueType ������ı��ķ�Χ
*/
$TEMP = imagettfbbox($this->FONT_SIZE,0,$this->FONT_PATH,$this->WORD);
$WORD_LENGTH=strlen($this->WORD);
$WORD_WIDTH =$TEMP[2] - $TEMP[6];
$WORD_HEIGHT =$TEMP[3] - $TEMP[7];
/*
����ˮӡ��Ĭ��λ��Ϊ���½�
*/
if($this->WORD_X==""){
   $this->WORD_X=$this->PICTURE_WIDTH-$WORD_WIDTH;
}
if($this->WORD_Y==""){
   $this->WORD_Y=$this->PICTURE_HEIGHT-$WORD_HEIGHT;
}
imagesettile($this->TRUE_COLOR,$this->PICTURE_CREATE);
imagefilledrectangle($this->TRUE_COLOR,0,0,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT,IMG_COLOR_TILED);
$TEXT2=imagecolorallocate($this->TRUE_COLOR,$this->RED,$this->GREEN,$this->BLUE);
imagettftext($this->TRUE_COLOR,$this->FONT_SIZE,$this->ANGLE,$this->WORD_X,$this->WORD_Y,$TEXT2,$this->FONT_PATH,$this->WORD);
}

/*
ˮӡͼƬ
*/
function _mark_picture(){

/*
��ȡˮӡͼƬ����Ϣ
*/
@$SIZE=getimagesize($this->FORCE_URL);
if(!$SIZE){
   exit($this->ERROR['unalviable']);
}
$FORCE_PICTURE_WIDTH=$SIZE[0];
$FORCE_PICTURE_HEIGHT=$SIZE[1];
//����ˮӡͼƬ
switch($SIZE[2]){
   case 1:
    $FORCE_PICTURE_CREATE=imagecreatefromgif($this->FORCE_URL);
    $FORCE_PICTURE_TYPE="gif";
    break;
   case 2:
    $FORCE_PICTURE_CREATE=imagecreatefromjpeg($this->FORCE_URL);
    $FORCE_PICTURE_TYPE="jpg";
    break;
   case 3:
    $FORCE_PICTURE_CREATE=imagecreatefrompng($this->FORCE_URL);
    $FORCE_PICTURE_TYPE="png";
    break;
}
/*
    �ж�ˮӡͼƬ�Ĵ�С��������Ŀ��ͼƬ�Ĵ�С�����ˮӡ��ͼƬ��������ͼƬ��СΪˮӡͼƬ�Ĵ�С���������ɵ�ͼƬ��СΪԭͼƬ��С��
*/
$this->NEW_PICTURE=$this->PICTURE_CREATE;
if($FORCE_PICTURE_WIDTH>$this->PICTURE_WIDTH){
   $CREATE_WIDTH=$FORCE_PICTURE_WIDTH-$this->FORCE_START_X;
}else{
   $CREATE_WIDTH=$this->PICTURE_WIDTH;
}
if($FORCE_PICTURE_HEIGHT>$this->PICTURE_HEIGHT){
   $CREATE_HEIGHT=$FORCE_PICTURE_HEIGHT-$this->FORCE_START_Y;
}else{
   $CREATE_HEIGHT=$this->PICTURE_HEIGHT;
}
/*
    ����һ������
*/
$NEW_PICTURE_CREATE=imagecreatetruecolor($CREATE_WIDTH,$CREATE_HEIGHT);
$WHITE=imagecolorallocate($NEW_PICTURE_CREATE,255,255,255);
/*
    ������ͼ������������
*/
imagecopy($NEW_PICTURE_CREATE, $this->PICTURE_CREATE, 0, 0, 0, 0,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);

/*
    ��Ŀ��ͼƬ����������ͼƬ��
*/
imagecopy($NEW_PICTURE_CREATE, $FORCE_PICTURE_CREATE, $this->FORCE_X, $this->FORCE_Y, $this->FORCE_START_X, $this->FORCE_START_Y,$FORCE_PICTURE_WIDTH,$FORCE_PICTURE_HEIGHT);
$this->TRUE_COLOR=$NEW_PICTURE_CREATE;
}
#end of mark

function alpha_(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
$rgb="#CDCDCD";
$tran_color="#000000";
for($j=0;$j<=$this->PICTURE_HEIGHT-1;$j++){
   for ($i=0;$i<=$this->PICTURE_WIDTH-1;$i++)
   {
    $rgb = imagecolorat($this->PICTURE_CREATE,$i,$j);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    $now_color=imagecolorallocate($this->PICTURE_CREATE,$r,$g,$b);
    if ($now_color==$tran_color)
    {
     continue;
    }
    else
    {
     $color=imagecolorallocatealpha($this->PICTURE_CREATE,$r,$g,$b,$ALPHA);
     imagesetpixel($this->PICTURE_CREATE,$ALPHA_X+$i,$ALPHA_Y+$j,$color);
    }
    $this->TRUE_COLOR=$this->PICTURE_CREATE;

   }
}
}

/*
ͼƬ��ת:
��y����ת
*/
function turn_y(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
for ($x = 0; $x < $this->PICTURE_WIDTH; $x++)
{
   imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, $this->PICTURE_WIDTH - $x - 1, 0, $x, 0, 1, $this->PICTURE_HEIGHT);
}
}

function turn_r1(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_HEIGHT,$this->PICTURE_WIDTH);
//exit("==".$this->PICTURE_WIDTH);
for ($x = 0; $x < $this->PICTURE_WIDTH; $x+=1)
{
for($y = 0; $y < $this->PICTURE_HEIGHT; $y+=1){
   imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, $y, $x, $this->PICTURE_WIDTH-$x, $this->PICTURE_HEIGHT-$y, 1,1);
   }
}
}
function turn_r2(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_HEIGHT,$this->PICTURE_WIDTH);
//exit("==".$this->PICTURE_WIDTH);
for ($x = 0; $x < $this->PICTURE_WIDTH; $x+=1)
{
for($y = $this->PICTURE_HEIGHT; $y >0; $y-=1){
   imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, $y, $x, $x, $y, 1,1);
   }
}
}

/*
��X����ת
*/
function turn_x(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
for ($y = 0; $y < $this->PICTURE_HEIGHT; $y++)
{
   imagecopy($this->TRUE_COLOR, $this->PICTURE_CREATE, 0, $this->PICTURE_HEIGHT - $y - 1, 0, $y, $this->PICTURE_WIDTH, 1);
}
}


/*
����Ƕ���ת
*/
function turn(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
imageCopyResized($this->TRUE_COLOR,$this->PICTURE_CREATE,0,0,0,0,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
$WHITE=imagecolorallocate($this->TRUE_COLOR,255,255,255);
$this->TRUE_COLOR=imagerotate ($this->TRUE_COLOR, $this->CIRCUMROTATE, $WHITE);
}
/*
ͼƬ��
*/
function sharp(){
$this->TRUE_COLOR=imagecreatetruecolor($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
$cnt=0;
for ($x=0; $x<$this->PICTURE_WIDTH; $x++){
   for ($y=0; $y<$this->PICTURE_HEIGHT; $y++)
   {
    $src_clr1 = imagecolorsforindex($this->TRUE_COLOR, imagecolorat($this->PICTURE_CREATE, $x-1, $y-1));
    $src_clr2 = imagecolorsforindex($this->TRUE_COLOR, imagecolorat($this->PICTURE_CREATE, $x, $y));
    $r = intval($src_clr2["red"]+$this->SHARP*($src_clr2["red"]-$src_clr1["red"]));
    $g = intval($src_clr2["green"]+$this->SHARP*($src_clr2["green"]-$src_clr1["green"]));
    $b = intval($src_clr2["blue"]+$this->SHARP*($src_clr2["blue"]-$src_clr1["blue"]));
    $r = min(255, max($r, 0));
    $g = min(255, max($g, 0));
    $b = min(255, max($b, 0));
    if (($DST_CLR=imagecolorexact($this->PICTURE_CREATE, $r, $g, $b))==-1)
    $DST_CLR = imagecolorallocate($this->PICTURE_CREATE, $r, $g, $b);
    $cnt++;
    if ($DST_CLR==-1) die("color allocate faile at $x, $y ($cnt).");
    imagesetpixel($this->TRUE_COLOR, $x, $y, $DST_CLR);
   }
}
}

/*
   ��ͼƬ��ɫ����??
*/
function return_color(){
/*
    ����һ������
*/
$NEW_PICTURE_CREATE=imagecreate($this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
$WHITE=imagecolorallocate($NEW_PICTURE_CREATE,255,255,255);
/*
    ������ͼ������������
*/
imagecopy($NEW_PICTURE_CREATE, $this->PICTURE_CREATE, 0, 0, 0, 0,$this->PICTURE_WIDTH,$this->PICTURE_HEIGHT);
$this->TRUE_COLOR=$NEW_PICTURE_CREATE;
}

/*
����Ŀ��ͼƬ����ʾ
*/
function show(){
// �ж������,����IE�Ͳ�����ͷ
if(isset($_SERVER['HTTP_USER_AGENT']))
{
   $ua = strtoupper($_SERVER['HTTP_USER_AGENT']);
   if(!preg_match('/^.*MSIE.*\)$/i',$ua))
   {
    header("Content-type:$this->PICTURE_MIME");
   }
}
$OUT=$this->PICTURE_TYPE;
$OUT($this->TRUE_COLOR);
}

/*
����Ŀ��ͼƬ������
*/
function save_picture($showpic=0){
// �� JPEG ��ʽ��ͼ���������������ļ�
$OUT=$this->PICTURE_TYPE;
if(function_exists($OUT)){
   // �ж������,����IE�Ͳ�����ͷ
   if(isset($_SERVER['HTTP_USER_AGENT']))
   {
    $ua = strtoupper($_SERVER['HTTP_USER_AGENT']);
    if(!preg_match('/^.*MSIE.*\)$/i',$ua))
    {
     header("Content-type:$this->PICTURE_MIME");
    }
   }
   if(!$this->TRUE_COLOR){
    exit($this->ERROR['unavilable']);
   }else{
    $OUT($this->TRUE_COLOR,$this->DEST_URL);
if($showpic){
   $OUT($this->TRUE_COLOR);
}
   }
}
}
/*
�����������ͷ�ͼƬ
*/
function __destruct(){
/*�ͷ�ͼƬ*/
@imagedestroy($this->TRUE_COLOR);
@imagedestroy($this->PICTURE_CREATE);
}
#end of class
}
?>

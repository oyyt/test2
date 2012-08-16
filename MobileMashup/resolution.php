<?php
/*author:   OuyangYutong
  function: Zoom photo in proportion 
*/

class Image {
	/**
	 * @param $filepath The file path of image file.
	 * @param $maxlen The max size of width or height of object image.
	 * @return false if resize fail.
	 *         true if resize success.
	 */
	public static function resize($filepath, $maxLen){
		if(!is_file($filepath)){
			return false;
		}
		$info = getimagesize($filepath);
		if($info === false){
			return false;
		}

		//if image size is little than $maxLen, then do nothing.
		if(max($info[0], $info[1]) < $maxLen){
			return true;
		}

		$img = null;
		$newImg = null;
		switch($info[2]){
		case 1:
			$img = imagecreatefromgif($filepath);
			break;
		case 2:
			$img = imagecreatefromjpeg($filepath);
			break;
		case 3:
			$img = imagecreatefrompng($filepath);
			break;
		default:
			return false;
		}

		$radio = max($info[0], $info[1]) / $maxLen;
		if($info[0] >= $info[1]){
			$newImg = imagecreatetruecolor($maxLen, (int)($info[1] / $radio));
			imagecopyresized($newImg, $img, 0, 0, 0, 0, $maxLen, (int)($info[1] / $radio), $info[0], $info[1]);
		}
		else{
			$newImg = imagecreatetruecolor((int)($info[0] / $radio), $maxLen);
			imagecopyresized($newImg, $img, 0, 0, 0, 0, (int)($info[0] / $radio), $maxLen, $info[0], $info[1]);
		}

		ImageJpeg($newImg, $filepath);
		ImageDestroy($img);
		ImageDestroy($newImg);

		return true;
	}
}

function resolution($url){
//	Image::resize('C:\Users\Libitum\Desktop\abc.jpg', 500);
    Image::resize($url, 300);
}
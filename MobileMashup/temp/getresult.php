<?php
	
	$result = array(
		'stat' => 'ok',
		'data' => array(
			'invoke1' => array(
				array(
					'id' => 1,
					'title' => 'oyyt'
				),
				array(
					'id' => 2,
					'title' => 'liuna'
				),
			),
			'invoke2' => array(
				array(
					'id' => 1,
					'url' => "http://192.168.2.168/MobileMashup/image/1.jpg",
				),
				array(
					'id' => 2,
					'url' => "http://192.168.2.168/MobileMashup/image/2.jpg",
				),
			),
		)
	);
	$json = json_encode($result);
	//手机拿到echo的东东
	echo $json;
//
//echo "<pre>";
//print_r($result);
//echo "</pre>";
//
//echolllllllllllllllllllllllllllllllllllllllllllll;
//require_once "manage.php";
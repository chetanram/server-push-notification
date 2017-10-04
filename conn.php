<?php



error_reporting(0);
session_start();
include("functions/functions.php");
define('FIREBASE_API_KEY', 'AAAAgYgf54s:APA91bEgFXqDrunaaJSv_jK5FqarkF6R-7rJ7ClJHwO31C_Fc9O_FbMK9YVfAkg6KPJJQAoKm4mVkUgpSPx594IJv62HsZlBaM66dCeEoEofCbKhZNuVgRD_j4m8hf9hNYFOHh8xjSDF');
$sever = $_SERVER['HTTP_HOST'];
if($sever == "192.168.1.1"){
	$con = mysqli_connect('localhost', 'root', 'root', 'lottery');
	DEFINE('IMAGE_URL',"http://192.168.1.1/lottery/admin/gallery/");
	DEFINE('LOTTORY_URL',"http://192.168.1.1/lottery/admin/images/");

	//DEFINE('UPLOAD_URL',"admin/gallery/");
	//define('UPLOAD_URL', $_SERVER['DOCUMENT_ROOT'] . '/lottery/admin/gallery/');
}else if($sever == "dev.acquaintsoft.com"){
	$con = mysqli_connect('mysql.acquaintsoft.com', 'agc_user', '*75A9xTea', 'lottery_app');
	DEFINE('IMAGE_URL',"http://dev.acquaintsoft.com/lottery/admin/gallery/");
	DEFINE('LOTTORY_URL',"http://dev.acquaintsoft.com/lottery/admin/images/");

	//DEFINE('UPLOAD_URL',"admin/gallery/");
	define('UPLOAD_URL', $_SERVER['DOCUMENT_ROOT'] . '/lottery/admin/gallery/');
}
?>
<?php
$_relativePath = "../../";

include_once $_relativePath."includes/db/server.php";
include_once $_relativePath."includes/_functions/functions.php";
require $_relativePath.'src/facebook.php';

$facebook = new Facebook(array(
  'appId'  => APPID,
  'secret' => APPSECRETID,
  'cookie' => true,
  'fileUpload' => true,
  'domain' => 'domainforssl.hu'
));

$user = $facebook->getUser();
if ($user) {
  try {
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

$output = array();
$decorations = myPost('decorations');
$userkey = myPost('tempkey');

//myPre($decorations);
$_decorations = explode(",", $decorations);


$_filename = generatePictureForRace( $user_profile['name'], $user, $userkey, $_decorations, $_relativePath );

if($_filename){

	/*****Publish to the wall********/
s	
	$_uzenet = "Ezt most díszítettem fel! Karácsony előtt pedig lehet, hogy én nyerem meg az ingyenes tárhelyet 1 évre!";
		//$_uzenet = "Ezt most csináltam. Ha tetszik, szavaznál rá egyet? Köszi! ";

	try {
			$facebook->setFileUploadSupport(true);
			//$img = SITE_URL.'images/result/food01.jpg';
			$photo = $facebook->api('/me/photos', 'POST',
									array( 'source' => '@' . $_relativePath.'temp/'.$_filename,
										   'message' => $_uzenet." Te is játszhatsz: ".EMBEDDING_PAGE
								   ));
	
		  }
		  catch (FacebookApiException $e) {
			error_log('Could not post image to Facebook.');
		  }
		  
		  @unlink( $_relativePath.'temp/'.$_filename );
	  
	$output['result'] = '<img src="images/feedback_ok.png">';
}else{
	$output['result'] = '<img src="images/feedback_no.png">';
}
		  
echo json_encode( $output );

include_once $_relativePath."includes/db/server_close.php";
?>
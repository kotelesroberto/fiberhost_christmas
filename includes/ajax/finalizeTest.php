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
$points = myPost('points');
$output['result'] = $points;

$prev_total_points = getTotalPointsOfThisUser( $user );
$_result = doWritePointsIntoDB( $user, $user_profile, $points);
$total_points = getTotalPointsOfThisUser( $user );

$output['prev_total_points'] = $prev_total_points;
$output['total_points'] = $total_points;
//$output['result'] = $_result;
/************CSAK ELSŐ HASZNÁLATKOR KÜLDÖM A FALÁRA!!!!***************/

if( $_result ){
	$_filename = generateDiploma(  $user_profile['name'], $points, 'diploma.jpg', $_relativePath  );
	
	$output['result'] = $points;
	
	/*****Publish to the wall********/
	
	$_uzenet = $user_profile['name']." ügyesen feldíszített egy fenyőfát ".$points." dísszel. Netán tudsz jobbat csinálni? Most ajándék tárhelyet érhet!";
	
	try {
			$facebook->setFileUploadSupport(true);
			//$img = SITE_URL.'images/result/food01.jpg';
			$photo = $facebook->api('/me/photos', 'POST',
									array( 'source' => '@' . $_relativePath.'temp/'.$_filename,
										   'message' => $_uzenet." És Te? ".EMBEDDING_PAGE
								   ));
	
		  }
		  catch (FacebookApiException $e) {
			error_log('Could not post image to Facebook.');
		  }
	
	@unlink( $_relativePath.'temp/'.$_filename );
}
echo json_encode( $output );

include_once $_relativePath."includes/db/server_close.php";
?>
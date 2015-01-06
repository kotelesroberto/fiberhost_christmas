<?php
/*************************
* File of used functions
* Köteles Róbert
* 2014
*************************/


define( 'DATEFORMAT', 'Y.m.d. H:i:s' );
define( 'APPID', '1525192097754247' );
define( 'APPSECRETID', '8954d423d4fd0f30bcf20922e720557e');
define( 'FANPAGEID', '195270190518716'); //RAJONGÓI OLDAL OBJECTID-JE
define( 'EMBEDDING_PAGE', 'https://www.facebook.com/fiberhosthu/app_1525192097754247' );
define( 'EMBEDDING_PAGE_RACE', 'https://apps.facebook.com/fiberhost_christmas/' );
define( 'LIKE_URL', 'http://domainforssl.hu/facebook/fiberhost_christmas/' ); 

$pos = strrpos( $_SERVER["HTTP_REFERER"] , "ttps://");
if($pos){
	define( 'SITE_URL', 'https://domainforssl.hu/facebook/fiberhost_christmas/' ); 
	define( 'SSL_URL', 'https://domainforssl.hu/facebook/fiberhost_christmas/' ); 
}else{
	define( 'SITE_URL', 'http://domainforssl.hu/facebook/fiberhost_christmas/' ); 
	define( 'SSL_URL', 'https://domainforssl.hu/facebook/fiberhost_christmas/' ); 
}

$db_prefix = "fiberhost_christmas";

/*DÍSZEK*/
$decors_array = array( 'st2.png', 'sb1.png', 's9.png', 's8.png', 's7.png', 's6.png', 's5.png', 's4.png', 's3.png', 's2.png', 's1.png', 'st1.png', 'd5.png', 'd4.png', 'd3.png', 'd2.png', 'd1.png', 'server_small.png', 'b12.gif', 'b11.gif', 'b10.gif', 'b9.gif', 'b8.gif', 'b5.gif', 'b4.gif' );

/************************************/
function getProtocol(){
	$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
	return $protocol;
}

function curPageURL( $type = 'full' ) {
	$_tempArray = array();
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	
	 $_parseURL = urlencode( $pageURL  );
	 $_parseURL = parse_url( $pageURL  );
	 if( $type == 'full') return $pageURL;
	 if( $type == 'host') return $_parseURL['scheme']."://".$_parseURL['host'];
}

function myPost( $_variable ){ 
	if(get_magic_quotes_gpc()){
		//echo "Magic quotes are enabled";
		return $_POST[$_variable];
	}else{
		//echo "Magic quotes are disabled";
		return addslashes($_POST[$_variable]);
	}
}

function myGet( $_variable ){ 
	if(get_magic_quotes_gpc()){
		//echo "Magic quotes are enabled";
		return $_GET[$_variable];
	}else{
		//echo "Magic quotes are disabled";
		return addslashes($_GET[$_variable]);
	}
}

function createDate( $string ){
	return date(DATEFORMAT , strtotime($string));
}

function myPre( $array ){
	echo "<pre style='text-align:left;'>";
	print_r($array);
	echo "</pre>";
}

function getFileExt($filename) {
	$names = explode('.', $filename);
	$i = count($names);
	if ($i>0) return  strtolower($names[$i-1]); else return '';
}
/***************************/
function generateNumber( $length = 20 ){
  $password = "";
  $possible = "0123456789"; 

  $i = 0;    
  while ($i < $length) { 
	$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
	if (!strstr($password, $char) )
	{ 
	  $password .= $char;
	  $i++;
	}
  }
  return $password;
}


function generateDiploma( $name, $points, $image, $relativePath = ''  ){
	/*NAME*/	
		$_name_width = strlen($name) * 14; //betű szélessége
		$_x = ( (500 - $_name_width) / 2 ) +60;
		$dest = generateNumber( 10 ).time().'.png';
		$image = $relativePath.'images/result/'.$image;
		$src = imagecreatefromjpeg($image);
		// Create some colors
		$orange = imagecolorallocate($src, 255, 229, 63);
		$grey = imagecolorallocate($src, 128, 128, 128);
		
		$font = $relativePath.'font/perpetua.ttf';
		
		// shadow
		imagettftext($src, 30, 0, $_x, 101, $grey, $font, $name);
		// text
		imagettftext($src, 30, 0, $_x, 100, $orange, $font, $name);
	
	/*POINTS*/
		
		$_x = 210;
		$dest = generateNumber( 10 ).time().'.png';
		// shadow
		imagettftext($src, 60, 0, 250, 155, $grey, $font, $points);
		// text
		imagettftext($src, 60, 0, 250, 154, $orange, $font, $points);
	
	imagesavealpha($src, true);
	imagejpeg($src, $relativePath.'temp/'.$dest, 90);
	@chmod($relativePath.'temp/'.$dest,0777); 
	
	return $dest;
}

function generatePictureForRace( $name, $user, $userkey, $_decorations, $_relativePath = '' ){
		global $db_prefix;
		/************/
		$query = " SELECT COUNT(id) AS darab FROM ".$db_prefix."_race_user WHERE ( userkey = '".$userkey."'  ) "; 
		$result = mysql_query($query);
		$row = mysql_fetch_assoc($result);
	
		if( $row['darab']  ){ 
			/*MÁR BENEVEZTE EZT AZ ALKOTÁST*/
			return '';
		}else{
			
			/*ALAPKÉP FELDOLGOZÁSA*/
			//$dest = generateNumber( 10 ).time().'.jpg';
			$dest = $userkey.'.jpg';
			$image = $_relativePath.'images/decoration/tree_to_generate.png';
			$src_dest = imagecreatefrompng($image);
			
			/*DÍSZEK HOZZÁADÁSA*/
			foreach($_decorations as $_decoration){			
				$decor_item = explode("|", $_decoration);			
				$image_for_copy = $_relativePath.'images/decoration/'.$decor_item[0];
				
				$stype = getFileExt($decor_item[0]);			
				if($stype == "jpg" || $stype == "jpeg") $src = imagecreatefromjpeg($image_for_copy);
				if($stype == "png") $src = imagecreatefrompng($image_for_copy);	
				if($stype == "gif") $src = imagecreatefromgif($image_for_copy);	
			
				list($width,$height)=getimagesize($image_for_copy); //a kép eredeti mérete
				imagecopy ( $src_dest , $src , $decor_item[1] , ($decor_item[2] + 20) , 0 , 0 , $width , $height );
			}
			
			imagesavealpha($src_dest, true);
			imagejpeg($src_dest, $_relativePath.'temp/'.$dest, 90);
			@chmod($_relativePath.'temp/'.$dest,0777); 
					
			$query_new = "INSERT INTO ".$db_prefix."_race_user VALUES ( '', '".$name."', now(), '".$user."', '0', '0', '".$userkey."', '1' )" ;				
			//mysql_query($query_new);
			return $dest;
		}
		/************/	
	
		
}

function doWritePointsIntoDB( $user, $user_profile, $points){
	global $db_prefix;
	
	$query = " SELECT COUNT(uid) AS darab FROM ".$db_prefix."_user WHERE ( uid = '".$user."'  ) "; 
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	
	$query_new = "INSERT INTO ".$db_prefix."_user VALUES ( '".$user."', '".$user_profile['name']."', '".$points."', now() )" ;				
	mysql_query($query_new);


	if( $row['darab']  ){ 
		/*MÉG ELŐSZÖR TÖLTI KI A TESZTET*/
		return false;
	}else{
		return true;
	}
}

function getTotalPointsOfThisUser( $user ){
	global $db_prefix;
	
	$query = " SELECT SUM(points) AS pontszam FROM ".$db_prefix."_user WHERE ( uid  = '".$user."'  ) "; 
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);
	//return $query;
	return $row['pontszam'];
}

?>
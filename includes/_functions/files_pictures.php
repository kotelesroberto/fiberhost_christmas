<?php
/*************************
* Functions of manipulating pictures
* Köteles Róbert
* 2014
*************************/


$_relativePath = $_relativePath?$_relativePath:"";

function getHeight($image){
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}

function getWidth($image){
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}

function getFileExt($filename) {
	$names = explode('.', $filename);
	$i = count($names);
	if ($i>0) return  strtolower($names[$i-1]); else return '';
}

function getFileName($filename) {
$names = explode('.', $filename);
$fileName = "";
for($t=0; $t < (count($names)-1); $t++) {$fileName .= $names[$t]; }
return  strtolower($fileName);
}

function getSafefileName($fileName) {
	$safe = '';
	for ($i=0, $n=strlen($fileName); $i<$n; $i++) {
	$c = $fileName[$i];
	if (ord($c) < 128 && ord($c) > 32)
	$safe .= $c;
	}
	if ($safe=='') $safe = '0';
	
	$find = array("!", "?", ",", "-");
	
	$safe = str_replace($find, "_", $safe);
	
	return $safe;
}

function getNoDuplicateFileName($pathName, $fileName, $extName) {
	$i = 0;
	$newFileName = $fileName;
	while (file_exists("$pathName/$newFileName.$extName")) {
		$newFileName = $fileName . (++$i);
	}
	$fileName = $newFileName;
	return $fileName;
}

function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
       
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function thumbImage($width_uj, $height_uj, $image, $stype, $dest, $relativePath = '') {

		if($stype == "jpg" || $stype == "jpeg") $src = imagecreatefromjpeg($image);
		if($stype == "png") $src = imagecreatefrompng($image);		  
		if($stype == "gif") $src = imagecreatefromgif ($image);		  
		//imagefilter ($src, IMG_FILTER_CONTRAST, 10);
		//imagefilter ($src, IMG_FILTER_BRIGHTNESS, 10);
		
		list($width,$height)=getimagesize($image); //a kép eredeti mérete

		if($width>$height)
			{
			$newwidth=$width_uj;
			$newheight= ($newwidth * $height) / $width;
				if( $newheight > $height_uj ){
					$newheight=$height_uj;
					$newwidth= ($newheight * $width) / $height;
				}
			}
			else
			{
			$newheight=$height_uj;
			$newwidth= ($newheight * $width) / $height;
			}

			
			$im_dest = imagecreatetruecolor($newwidth,$newheight);
			imagealphablending($im_dest, false);
			
			imagecopyresampled($im_dest,$src,0,0,0,0,$newwidth,$newheight,$width,$height); //innen az átméretezett kép a $tmp változóban van
			
			imagesavealpha($im_dest, true);
			imagepng($im_dest, $dest);
			return true;
}

function thumbAndCropImage( $width_uj, $height_uj, $image, $stype, $dest, $_relativePath = '' ){

list($width, $height) = getimagesize($image);

if($stype == "jpg" || $stype == "jpeg") $src = imagecreatefromjpeg($image);
if($stype == "png") $src = imagecreatefrompng($image);
if($stype == "gif") $src = imagecreatefromgif ($image);		  

if( ($width/$height) < ($width_uj/$height_uj) ){ //TEHÁT SZÉLESSÉGÉBEN KELL DOLGOZNI VELE
	 $_widthThumb = $width_uj;
	 $_heightThumb = $height / ($width / $_widthThumb);
}else{
	 $_heightThumb = $height_uj;
	 $_widthThumb = $width / ($height / $_heightThumb);
}

	$im_tmp = imagecreatetruecolor($_widthThumb,$_heightThumb);
	imagealphablending($im_tmp, false);			
	imagecopyresampled($im_tmp,$src,0,0,0,0,$_widthThumb,$_heightThumb,$width,$height); //innen az átméretezett kép a $tmp változóban van
	
	imagesavealpha($im_tmp, true);
	
	///--------------------------------------------------------
	//setting the crop size
	//--------------------------------------------------------
	if($_widthThumb > $_heightThumb) $biggestSide = $_widthThumb;
	else $biggestSide = $_heightThumb;
	
	//The crop size will be half that of the largest side
	$cropPercent = 1;
	$cropWidth   = $width_uj*$cropPercent;
	$cropHeight  = $height_uj*$cropPercent;
	
	//getting the top left coordinate
	$c1 = array("x"=>($_widthThumb-$cropWidth)/2, "y"=>($_heightThumb-$cropHeight)/2);
	//--------------------------------------------------------
	// Creating the thumbnail
	//--------------------------------------------------------
	
	/**/
	$im_dest = imagecreatetruecolor($width_uj, $height_uj);
	imagealphablending($im_dest, false);

	imagecopyresampled($im_dest,$im_tmp, 0, 0, $c1['x'], $c1['y'], $width_uj, $height_uj, $cropWidth, $cropHeight);
	
	imagesavealpha($im_dest, true);
	imagepng($im_dest, $dest);
	return true;
}

function uploadFile($conname, $directory = "photos", $sizeW = "240", $sizeH = "180", $_mode = "resize" ){
	//KÉPEK FELTÖLTÉSE
	  $filetipus = array( "jpg", "jpeg",  "png", "gif");
	  $maxfilesize = return_bytes(ini_get('post_max_size'));
	  
	  $newfilename = "";
	
	  $filetype = $_FILES[$conname]['type'];
	  $filename = $_FILES[$conname]['name'];

		$temp_img = explode(".",  $filename);	
		$kiterjesztes = getFileExt($filename);
	  	
		if ($filename != ''){
			if (in_array($kiterjesztes,$filetipus)){
				$filesize = $_FILES[$conname]['size'];
				if($filesize <= $maxfilesize ){	
		
					$newfilename = getNoDuplicateFileName( $directory."/original", getSafefileName(getFileName($filename)), $kiterjesztes ).".".$kiterjesztes;			

					$source = $_FILES[$conname]['tmp_name'];
					$target = $directory."/original/".$newfilename;
					@move_uploaded_file($source, $target);
					if (file_exists($target))
					{			
					@chmod($target,0777); 
					//resize picture
							if($_mode == "resize"){
							thumbImage( 115, 125, $directory."/original/".$newfilename, $kiterjesztes, $directory."/thumb/".$newfilename);
							thumbImage( 180, 125, $directory."/original/".$newfilename, $kiterjesztes, $directory."/thumb2/".$newfilename);
							//thumbImage($sizeW, $sizeH, $directory."/".$newfilename, $kiterjesztes, $directory."/".$newfilename);
							}else{ //CROP
							thumbAndCropImage(115, 125, $directory."/original/".$newfilename, $kiterjesztes, $directory."/thumb/".$newfilename);
							thumbImage( 180, 125, $directory."/original/".$newfilename, $kiterjesztes, $directory."/thumb2/".$newfilename);
							//thumbAndCropImage($sizeW, $sizeH, $directory."/".$newfilename, $kiterjesztes, $directory."/".$newfilename);
							}
							thumbImage($sizeW, $sizeH, $directory."/original/".$newfilename, $kiterjesztes, $directory."/".$newfilename);
					}		
				}
				else{
					echo " Túl nagy a fájl! ";
					}
			}
			else{
				echo "A feltöltött kép formátuma nem megfelelő.";
				}
		}
		return $newfilename;
}

?>
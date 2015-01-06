<?php
/*************************
* SKIN-CSERÉLŐ MOTOR
* Köteles Róbert
* 2013
*************************/

$SKIN_PATH = '/'.$GLOBALS["SKIN_DIR"].'/'.$GLOBALS["SKIN_NAME"].'';
	
function readskin($tokens, $variables, $sfile, $_relativePath = '') {
		global
			$GLOBALS;

		$_res="";
	
		$_SKIN_FILE=trim($sfile);

		$_SKINS_DIR=trim($GLOBALS["SKIN_DIR"]);
		if ($_SKINS_DIR<>"") {
			if (substr($_SKINS_DIR,-1,1)<>'/') {
				$_SKINS_DIR.="/";
			}
		}
		
		$_SKIN_NAME=trim($GLOBALS["SKIN_NAME"]);
		if (substr($_SKIN_NAME,-1,1)<>'/')
			$_SKIN_NAME.="/";
		
		$_SKIN_FILE = $_relativePath.$_SKINS_DIR.$_SKIN_NAME.$_SKIN_FILE;

		if (is_file($_SKIN_FILE)) {
			// létezik a file
			if ($fd=fopen($_SKIN_FILE, "r")) {		
				$_res="";
				while(!feof($fd)) 
					$_res.=fgets($fd,4096);
				fclose($fd);
			} else {
				$_res="";
			}
		} else {
			// nincs skin file
		}
		
		// tokenek (változók) és értékeik cseréje
		if ($_res<>"") {
			$_res=str_replace($tokens, $variables, $_res);
		}
		
		return($_res);
	}
?>
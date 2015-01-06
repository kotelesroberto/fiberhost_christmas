<?php
/**************************
*	Facebook game 
*	Köteles Róbert
*	2014
**************************/

session_start();
header('p3p: CP="ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV"');

require 'includes/db/server.php';
require 'includes/_functions/functions.php';
require 'src/facebook.php';


// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => APPID,
  'secret' => APPSECRETID,
  'cookie' => true,
  'domain' => 'domainforssl.hu'
));

/**************** Get User ID***************/
$user = $facebook->getUser();
if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
/******************************************/

/********ENGEDÉLYEK VIZSGÁLATA. HA NINCS MEGFELELŐ ENGEDÉLYE, AKKOR ENGEDÉLYKÉRŐ ABLAK**************/
if ( $user ) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
	
	$loginUrl = $facebook->getLoginUrl(array(
			'canvas' => 1,
			'fbconnect' => 0,
			'redirect_uri' => EMBEDDING_PAGE,
			'cancel_url' => EMBEDDING_PAGE,
			'scope'         => 'public_profile,publish_stream'
			//'scope'         => 'public_profile,publish_stream,user_about_me'
			
		));
		
	if( $_page == "permission" ){
	/*ENGEDÉLYKÉRÉS*/
		//echo $loginUrl;
		echo "<script type='text/javascript'>top.location.href = '".$loginUrl."';</script>";
	}

}
/****************************************************************************************************/
		
		/***** LIKE VIZSGÁLATA AZ ADOTT OLDALRA VONATKOZÓLAG********/
		try{
            $fql    =   "SELECT uid, page_id, type, created_time FROM page_fan WHERE uid='".$user."' AND page_id = '".FANPAGEID."' ";

            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );
            $fqlResult   =   $facebook->api($param);
			//echo "<pre>"; print_r($fqlResult); echo "</pre>";
			
			if( count($fqlResult) ){ $isFAN = 1; }else{ $isFAN = 0; }
        }
        catch(Exception $o){
            //d($o);
        }	
		/**********************************************************/
		//echo $user." FAN: ".$isFAN;
?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" lang="hu-HU"
 xmlns:og="http://opengraphprotocol.org/schema/"> 

<head>
	<title>Öltöztesd fel a karácsonyfádat!</title>    
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <meta name="author" content="Köteles Róbert" />
    <meta name="copyright" content="&copy; 2014 FIBERHOST-PLUS Kft." />
	<meta name="description" content="Öltöztesd fel kedvedre a saját karácsonyfádat! Ha tetszik, küldd be a fa-szépségversenyre!" />
    <link rel="shortcut icon" href="<?php echo SITE_URL;?>images/app_logo.jpg" type="image/x-icon" /> 
	<link rel="image_src" href="<?php echo SITE_URL;?>images/app_logo.jpg" />

	<meta property="og:title" content="Te milyen szépen tudod felöltöztetni a fádat 60 másodperc alatt?"/> 
	<meta property="og:description" content="Te milyen szépen tudod felöltöztetni a fádat 60 másodperc alatt?"/> 
	<meta property="og:image" content="<?php echo SITE_URL;?>images/app_logo.jpg"/> 
	<meta property="og:site_name" content="Öltöztesd fel a karácsonyfádat!"/> 	
    <meta property="og:url" content="<?php echo curPageURL('full');?>"/>
    <meta property="og:type" content="website"/> 	
    <meta property="fb:admins" content="1423475289" />
    <meta property="fb:app_id" content="<?php echo APPID;?>" />    
    <link rel="stylesheet" href="<?php echo SITE_URL;?>css/style.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo SITE_URL;?>css/countdowner.css" type="text/css" />
	<script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery-1.4.3.min.js"></script>
    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.easing.js"></script>
    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.lwtCountdown-1.0.js"></script>
    <script type="text/javascript" src="<?php echo SITE_URL;?>js/jquery.random.js"></script>
    

</head>

    <body>
    <div id="fb-root" style="width:0px; height:0px; overflow:hidden; position:relative;"></div>
    

    	<div class="mainwrapper">
        	<div class="area_start">
				
                <div class="move_santa_claus"></div>
                <div class="fields_layer"></div>
                <div class="got_bonus"></div>
                <div class="make_undo"></div>
                
                <div class="game_area">                	
                    <div class="christmas_tree"></div>
                </div>
                
                <?php if( !$user ){ ?>
                <div class="first_game">
                    <div class="start_button"><a target="_top" href="<?php echo $loginUrl;?>"><img src="images/transparent.png"></a></div>
	            </div>
                <?php } ?>
                
                <?php if($user && (0 && !$isFAN)){ ?>
                <div class="please_like">
                    <div class="panel_like">
                        <fb:like href="http://www.facebook.com/fiberhosthu" layout="button_count" show_faces="true" width="100" font="arial"></fb:like>
                    </div>
	            </div>
                
                <?php } ?>                

                <div class="new_game" <?php if( $user && (1 || $isFAN) ){ echo ' style="display:block;" '; } ?> >
                    <div class="start_button play_game"></div>
	            </div>
                
                <div class="restart_game">
                    <div class="end_total_points">0</div>
                    <div class="start_button play_game"></div>
                    <div class="icon_friends" onClick="fb_feed()"></div>
                    <div class="icon_nominate" onClick="nominateMyTree()"></div>
	            </div>
                
                
                
                <div class="target_count">0</div>
                
                <!-- Countdown dashboard start -->
                <div id="countdown_fields">
        
                    <div class="dash minutes_dash">
                        <span class="dash_title">perc</span>
                        <div class="digit">0</div>
                        <div class="digit">0</div>
                    </div>
        
                    <div class="dash seconds_dash">
                        <span class="dash_title">másodperc</span>
                        <div class="digit">0</div>
                        <div class="digit">0</div>
                    </div>
        
                </div>
                <!-- Countdown dashboard end -->
            	
            </div>
        
        </div>
    
<div class="sc_menu">
	

</div>

    
    </body>
</html>

<script type= "text/javascript">/*<![CDATA[*/
function makeScrollable(){
	//Get our elements for faster access and set overlay width
	var div = $('div.sc_menu'),
		ul = $('ul.sc_menu'),
		ulPadding = 15;
	
	//Get menu width
	var divWidth = div.width();

	//Remove scrollbars	
	div.css({overflow: 'hidden'});
	
	//Find last image container
	var lastLi = ul.find('li:last-child');
	
	//When user move mouse over menu
	div.mousemove(function(e){
		//As images are loaded ul width increases,
		//so we recalculate it each time
		var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;	
		var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
		div.scrollLeft(left);
	});
	
	 setClickImgInactive(); //set the new items as clickable item
}
/*]]>*/</script>

<script type="text/javascript" charset="utf-8">
function moveSanta(){
	//Animation of randomly displayed santa
	$('.move_santa_claus').css('left', $.randomBetween(0, 700));	
	setTimeout(function () {
		$('.move_santa_claus').animate({top: '-=40'}, 1000, 'linear'); 
	}, 5000 );	

	setTimeout(function () {
		$('.move_santa_claus').animate({top: '+=40'}, 1000, 'linear', function(){ moveSanta(); }  );
	}, 7000 );
	

}	
moveSanta();
</script>


<script type="text/javascript" charset="utf-8">
/*Variables*/
var total_points = 0;
var actual_decor_id = 0; /*Actual decoration's ID*/
var actual_decor_count = 0; /*Actual decoration's order number*/
var stackDecor= new Array(); /*Stack of used decorations for the UNDO function*/
var elements_on_stage = new Array(); /*Contains the ID of used elements for using remove() function*/
var elements_on_stage_array = new Array(); /*Contains the positions of used elements, is important for generating image at the end*/
var canNominate = 1; /*Nominate the final tree into the competition*/

var currentDate = new Date();
var tempkey;

<?php 
$decors = ""; 
for($i=0; $i<count($decors_array); $i++){ 
	$decors .= "'".$decors_array[$i]."',"; 
} 
$decors = substr($decors, 0, strlen($decors)-1);
?>
var decorations= [ <?php echo $decors; ?> ]; /*List of exist decorations*/

function setClickImgInactive(){
	$(".img_inactive").unbind('click').click(function(){
		$(".img_active").removeClass('img_active').addClass('img_inactive');
		$(this).removeClass('img_inactive').addClass('img_active');
		actual_decor_id = $(this).attr('rel');
	});
}

/*Click on the christmas tree*/
$(".christmas_tree").click(function(e){	
	var offset = $(this).offset();
	var x = e.pageX - Math.floor(offset.left ) ;
	var y = e.pageY - Math.floor(offset.top ) ;
	
	actual_decor_count++;
	$('.christmas_tree').append('<span id="stage_decor'+actual_decor_count+'" name="stage_decor'+actual_decor_count+'" class="game_item"><img id="img_stage_decor'+actual_decor_count+'" name="img_stage_decor'+actual_decor_count+'" src="images/decoration/'+decorations[actual_decor_id]+'"></span>');
	x = x - ($('#stage_decor'+actual_decor_count+'').width() / 2);
	y = y - ($('#stage_decor'+actual_decor_count+'').height() / 2);
	$('#stage_decor'+actual_decor_count+'').css( 'left', x+'px' ).css( 'top', y+'px' ).show();
	elements_on_stage.push( 'stage_decor'+actual_decor_count+'' );
	total_points++;
	$('.target_count').html( ''+total_points+'' );	
	//új objektum pozíciójának elhelyezése
	elements_on_stage_array.push( ''+decorations[actual_decor_id]+'|'+x+'|'+y+'' );
	 
});

/*Undo steps*/
$(".make_undo").click(function(e){	
	 var element_to_remove = elements_on_stage.pop();
	 elements_on_stage_array.pop();
	 
	 if(element_to_remove){
	 	$('#'+element_to_remove+'').remove();
		total_points--;
		$('.target_count').html(''+total_points+'');
	 }
});

/*Nominate into competition*/
function nominateMyTree(){
	if(canNominate){
		$('.end_total_points').html('<img src="images/loading_circle.gif">');
		canNominate = 0;
		$.post("includes/ajax/nominate.php", { 'decorations' : ''+elements_on_stage_array.toString()+'', 'tempkey' : '<?php echo $user;?>_'+tempkey+'' } ,function(data){
			$('.end_total_points').html(data.result);
			canNominate = 1;
			//alert('fejlesztés alatt...');
		}, 'json');
	}
}

//COUNTDOWN
jQuery(document).ready(function() {
	$('#countdown_fields').countDown({
		targetOffset: {
			'day': 		0,
			'month': 	0,
			'year': 	0,
			'hour': 	0,
			'min': 		1,
			'sec': 		0
		}
	});
});

// Stop countdown
function stop_timecount() {
	$('#countdown_fields').stopCountDown();
}

// Start countdown
function start_timecount() {
	$('#countdown_fields').startCountDown();
}

// reset
function reset_timecount() {
	$('#countdown_fields').stopCountDown();
	$('#countdown_fields').setCountDown({
		targetOffset: {
			'day': 		0,
			'month': 	0,
			'year': 	0,
			'hour': 	0,
			'min': 		1,
			'sec': 		0
		}
	});				
}

function replaceDecorations( prev_total_points, new_total_points ){
		if( Math.ceil(prev_total_points/100) != Math.ceil(new_total_points/100) ){
			$.post("includes/ajax/replaceItems.php", { 'prev_total_points' : ''+prev_total_points+'',  'new_total_points' : ''+new_total_points+'' } ,function(data){
				$('div.sc_menu').html(data.result);	
				makeScrollable();
				$('div.got_bonus').html('<img src="images/you_got_bonus.png">');
				
				setTimeout(function () { $('div.got_bonus').html(''); }, 5000 );	
				
			}, 'json');
		}
}

// reset and start
function restart_timecount() {
	$('#countdown_fields').stopCountDown();
	$('#countdown_fields').countDown({
		targetOffset: {
			'day': 		0,
			'month': 	0,
			'year': 	0,
			'hour': 	0,
			'min': 		1,
			'sec': 		0
		}, 
		// onComplete function
		onComplete: function() { 
			$('.restart_game').show();
			$('.end_total_points').html(''+total_points+'');
			/*SEND POINTS TO AJAX*/
			$.post("includes/ajax/finalizeTest.php", { 'points' : ''+total_points+'' } ,function(data){
				$('.end_total_points').html(data.result);
				replaceDecorations(data.prev_total_points,  data.total_points );		
			}, 'json');
		
		}
	});				
	//$('#countdown_fields').startCountDown();
}





$(document).ready(function () {
	reset_timecount();
	replaceDecorations( 0, 100 );
});

/**************************************/

$(".play_game").click(function(){ 		
	$('.game_item').remove();
	$('.new_game, .restart_game').hide();	
	
	/*RESET POINTS AND ARRAYS*/
	total_points = 0;
	elements_on_stage = [];
	elements_on_stage_array = [];

	$('.target_count').html( ''+total_points+'' );
	restart_timecount();
	
	var currentDate = new Date();
	tempkey = currentDate.getTime();
});


function getPermission(){
	top.location.href = "<?php echo $loginUrl;?>";
}

function fb_feed( displayType ) {
	FB.ui({
		method: 'stream.publish',
		user_message_prompt: "A következő üzenet kerül az üzenőfaladra:",
		message: "Karácsonyfa díszítésben verhetetlen vagyok! :))) ",
		display: displayType,
		attachment: {
		  name: 'Öltöztesd fel a karácsonyfádat, nyerj éves tárhelyet!',
		  caption: 'Te milyen szépen tudod felöltöztetni a fádat 60 másodperc alatt?',
		  description: (
			'Öltöztess karácsonyfát ízlésed szerint! Most ingyen éves tárhelyet érhet!'
		  ),
		  media : [{'type':'image','src':'http://domainforssl.hu/facebook/fiberhost_christmas/images/app_logo.jpg','href':'https://apps.facebook.com/fiberhost_christmas/'}],
		  href: 'https://apps.facebook.com/fiberhost_christmas/'
		},
		action_links: [
		  { text: 'Öltöztesd fel a karácsonyfádat! Most ingyen éves tárhelyet érhet!', href: 'https://apps.facebook.com/fiberhost_christmas/' }
		]
	  },
	  function(response) {
		if (response && response.post_id) {
		  //alert('Post was published.');
		} else {
		  //alert('Post was not published.');
		}
	  }
	);
	
}
</script>

<script type="text/javascript">
	window.fbAsyncInit = function() {
		FB.init({
		appId  : '<?php echo APPID;?>',
		status : true, // check login status
		cookie : true, // enable cookies to allow the server to access the session
		xfbml  : true // parse XFBML
		//channelUrl: "http://domainforssl.hu/facebook/karacsonyfa/channel.html" //custom channel
		});
	FB.Canvas.scrollTo(0,0);	
	FB.Event.subscribe('edge.create', function(response) { 
		$('.please_like').hide();
		$('.new_game').show();
	});
			
	};
	
	(function() {
	var e = document.createElement('script');
	e.src = document.location.protocol + '//connect.facebook.net/hu_HU/all.js#appId=<?php echo APPID;?>&xfbml=1';
	e.async = true;
	document.getElementById('fb-root').appendChild(e);
	}());	
	
</script>                
              
<?php
include ( 'includes/db/server_close.php' );
?>
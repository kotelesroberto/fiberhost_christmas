<?php
$_relativePath = "../../";

include_once $_relativePath."includes/db/server.php";
include_once $_relativePath."includes/_functions/functions.php";
require $_relativePath.'src/facebook.php';

$output = array();
$prev_total_points = myPost('prev_total_points');
$new_total_points = myPost('new_total_points');
$responseText = '';

$responseText .='<ul class="sc_menu">';
if( 350< $new_total_points) { $responseText .='<li><a href="#"><img src="images/decoration/b4.gif" alt="Cukorbot" rel="24" class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 300< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/b5.gif" alt="Cukorbot" rel="23" class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 250< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/b8.gif" alt="Cukorbot" rel="22" class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 200< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/b9.gif" alt="Cukorbot" rel="21" class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 150< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/b10.gif" alt="Cukorbot" rel="20" class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 100< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/b11.gif" alt="Cukorbot" rel="19"  class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 50< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/b12.gif" alt="Cukorbot" rel="18"  class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 20< $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/server_small.png" alt="Cukorbot" rel="17"  class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/d1.png" alt="Cukorbot" rel="16"  class="img_inactive"/><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/d2.png" alt="Cukorbot" rel="15"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/d3.png" alt="Cukorbot" rel="14"  class="img_active" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/d4.png" alt="Cukorbot" rel="13"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/d5.png" alt="Cukorbot" rel="12"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/st1.png" alt="Cukorbot" rel="11"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s1.png" alt="Cukorbot" rel="10"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s2.png" alt="Cukorbot" rel="9"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s3.png" alt="Cukorbot" rel="8"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s4.png" alt="Cukorbot" rel="7"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s5.png" alt="Cukorbot" rel="6"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s6.png" alt="Cukorbot" rel="5"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s7.png" alt="Cukorbot" rel="4"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s8.png" alt="Cukorbot" rel="3"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/s9.png" alt="Cukorbot" rel="2"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/sb1.png" alt="Cukorbot" rel="1"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }
if( 0 < $new_total_points ) { $responseText .='<li><a href="#"><img src="images/decoration/st2.png" alt="Cukorbot" rel="0"  class="img_inactive" /><span><img src="images/bottom_arrow.png"></span></a></li>'; }

$responseText .='</ul>';

$output['result'] = $responseText;
echo json_encode( $output );

include_once $_relativePath."includes/db/server_close.php";
?>
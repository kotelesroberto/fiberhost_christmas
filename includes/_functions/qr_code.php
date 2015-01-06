<?php

function generateQRwithGoogle($text, $alttext='', $widthHeight ='150',$EC_level='L',$margin='0') {
    $text = urlencode($text); 
    return '<img alt="'.$alttext.'" title="'.$alttext.'"  src="http://chart.apis.google.com/chart?chs='.$widthHeight.
'x'.$widthHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.
'&chl='.$text.'"  widthHeight="'.$widthHeight.
'" widthHeight="'.$widthHeight.'"/>';
}

?>
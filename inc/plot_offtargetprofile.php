<?php 
// plot the offtarget profile of a given guide, handed over in an associative array with indices target_chr, target_start, target_end, cleavage_freq

function plotOfftargetProfile($array) {
    // generate image
    $image = imagecreate(200, 80);
    $background = imagecolorallocate($image, 0, 0, 255);
    $text_colour = imagecolorallocate($image, 255, 255, 0);
    $line_colour = imagecolorallocate($image, 128, 255, 0);
    imagestring($image, 4, 30, 25, "test", $text_colour);
    imagesetthickness ($image, 5);
    imageline($image, 30, 45, 165, 45, $line_colour);
    
    // return as data URI
    ob_start(); // need to buffer image output to encode it later
    imagepng($image);
    $contents =  ob_get_clean();
    return ('data:' . $mime . ';base64,' . base64_encode($contents));
}

echo '<img src="'.plotOfftargetProfile(0).'" alt="offtarget_distr" />';

?>
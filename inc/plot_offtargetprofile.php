<?php 
// plot the offtarget profile of a given guide, handed over in an associative array with indices target_chr, target_start, target_end, cleavage_freq

$chromLengths = array("chr1"=>224999719, "chr2"=>237712649, "chr3"=>194704827, "chr4"=>187297063, "chr5"=>177702766, "chr6"=>167273993, "chr7"=>154952424, "chr8"=>142612826, "chr9"=>120312298, "chr10"=>131624737, "chr11"=>131130853, "chr12"=>130303534, "chr13"=>95559980, "chr14"=>88290585, "chr15"=>81341915, "chr16"=>78884754, "chr17"=>77800220, "chr18"=>74656155, "chr19"=>55785651, "chr20"=>59505254, "chr21"=>34171998, "chr22"=>34893953, "chrX"=>151058754, "chrY"=>25121652);
$totalLength = array_sum($chromLengths);

function drawBorder(&$img, &$color, $thickness=1) {
    imagesetthickness($img, 1);
    $x1 = 0;
    $y1 = 0;
    $x2 = imagesx($img) - 1;
    $y2 = imagesy($img) - 1;
    
    for($i = 0; $i < $thickness; $i++) {
        imagerectangle($img, $x1++, $y1++, $x2--, $y2--, $color);
    }   
}

function getXcoord($chr, $start, $imgwidth) {
    $chromLengths = $GLOBALS['chromLengths']; // get global variables
    $totalLength  = $GLOBALS['totalLength'];
    $index = array_search($chr, array_keys($chromLengths));
    if ($index >= 0 && is_numeric($start)) {
        $LengthToChr = array_sum(array_chunk($chromLengths, $index+1, TRUE)[0])-$chromLengths["chr1"];
        $xpos = $imgwidth*($LengthToChr+$start)/$totalLength;
        return ceil($xpos);
    } else { return FALSE; }
}

function plotOfftargetProfile($array, $imgwidth=180, $imgheight=30) {
    // generate image
    $image = imagecreate($imgwidth, $imgheight);
    $background    = imagecolorallocate($image, 255, 255, 255); // white
    $target_colour = imagecolorallocate($image, 0, 0, 0);       // black
    $weak_colour = imagecolorallocate($image, 110, 110, 110);       // gray
    $guide_colour  = imagecolorallocate($image, 128, 255, 0);   // green
    $border_colour = imagecolorallocate($image, 0, 0, 0);       // black
    
    // get sum of cleavage frequencies
    $cfsum = 0;
    $ontarget_cf = 1;
    foreach ($array as $target) { 
        $cfsum += $target["cleavage_freq"];
        if ($target["id"] == $target["grna_target_id"]) { $ontarget_cf = $target["cleavage_freq"]; }
    }
    if ($ontarget_cf == 0) { $ontarget_cf = 1; }
    
    // draw vertical line for each target
    foreach ($array as $target) {
        // calculate x position of line from chromosome and start
        $xpos = getXcoord($target["target_chr"], $target["target_start"], $imgwidth);
        if ($xpos >= 0) {
            imagesetthickness($image, 2);
            if ($target["cleavage_freq"] > 0.1) { $colour = $target_colour; }
            else { imagesetthickness($image, 1);  $colour = $weak_colour; }
            $barheight = intval($imgheight * $target["cleavage_freq"] / $ontarget_cf);
            if ($barheight > $imgheight) { $barheight = $imgheight; }
            elseif ($barheight < 0) { $barheight = 0; }
            imageline($image, $xpos, $imgheight, $xpos, $imgheight-$barheight, $colour);
        }
    }
    // draw line for the guide
    imagesetthickness($image, 3);
    $guidexpos = getXcoord($target["grna_target_chr"], $target["grna_target_start"], $imgwidth);
    imageline($image, $guidexpos, 0, $guidexpos, $imgheight, $guide_colour);
    
    drawBorder($image, $border_colour);
    
    // return as data URI
    ob_start(); // need to buffer image output to encode it later
    imagepng($image);
    $contents =  ob_get_clean();
    return ('data:' . $mime . ';base64,' . base64_encode($contents));
}

//echo '<img src="'.plotOfftargetProfile(array(["target_chr"=>"chr10", "target_start"=>"10000"])).'" alt="offtarget_distr" />';

?>
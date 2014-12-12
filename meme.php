<?php

///////////////////////////////////////////////////////////////////
require_once("libs/tools.php");
define("POSITION_TOP",2); //Imagick::GRAVITY_NORTH
define("POSITION_BOTTOM",8); //Imagick::GRAVITY_SOUTH

///////////////////////////////////////////////////////////////////
$meme = ["A7TaY8HWYd8",71 ,5, "PRETTY GOOD JUNIOR"];
//$meme = ["6Hn8qnsucwo",30,5, "PRETTY ... PRETTY ... PRETTY GOOD !"];
//$meme = ["_tj5ye2r8RI",30,5, "PRETTY ... PRETTY ... PRETTY GOOD !"];
//$meme = ["yLAjKtmT3lk",30,5, "PRETTY ... PRETTY ... PRETTY ... PRETTY ... PRETTY GOOD !"];
$width = 500;
$fontSize = 40;
$color = "white";
$borderColor = "black";

//////////////////////////////////////////////////////////////////
$videoID = $meme[0];
$from = $meme[1];
$duration = $meme[2];
$text = $meme[3];
$fontFile = "assets/impact.ttf";
$gifFile = "data/".$videoID.".gif";
$gifFileFinal = "data/".$videoID."-final.gif";
$yOffset = 10;
$xOffset = 0;
$position = POSITION_TOP;

///////////////////////////////////////////////////////////////////
$result = run("node","libs/youtubeToGif/youtubeToGif.js",$videoID,$width,$from,$duration);
if($result["code"] != 0) {
    die("youtube to gif failed");
}
$gif = new Imagick($gifFile);
$draw = new ImagickDraw();
$draw->setGravity($position);
$draw->setStrokeColor($borderColor);
$draw->setStrokeWidth(2);
$draw->setStrokeAntialias(true);
$draw->setTextAntialias(true); 
$draw->setFont($fontFile);
$draw->setFontSize($fontSize);
$draw->setFillColor($color);
foreach($gif as $frame) {
    list($lines, $lineHeight) = wordWrapAnnotation($gif, $draw, $text, $width - 20);
    if($position == POSITION_BOTTOM) {
        $lines = array_reverse($lines);
    }
    for($i = 0; $i < count($lines); $i++) {
        $gif->annotateImage($draw, $xOffset, $yOffset+$i*$lineHeight, 0, $lines[$i]);
    }
}    
$gif->writeImages($gifFileFinal,true);
echo "meme generated to ".$gifFileFinal;

///////////////////////////////////////////////////////////////////
function wordWrapAnnotation(&$image, &$draw, $text, $maxWidth)
{
    $words = explode(" ", $text);
    $lines = array();
    $i = 0;
    $lineHeight = 0;
    while($i < count($words) )
    {
        $currentLine = $words[$i];
        if($i+1 >= count($words))
        {
            $lines[] = $currentLine;
            break;
        }
        $metrics = $image->queryFontMetrics($draw, $currentLine . ' ' . $words[$i+1]);
        while($metrics['textWidth'] <= $maxWidth)
        {
            $currentLine .= ' ' . $words[++$i];
            if($i+1 >= count($words))
                break;
            $metrics = $image->queryFontMetrics($draw, $currentLine . ' ' . $words[$i+1]);
        }
        $lines[] = $currentLine;
        $i++;
        if($metrics['textHeight'] > $lineHeight)
            $lineHeight = $metrics['textHeight'];
    }
    return array($lines, $lineHeight);
}

?>
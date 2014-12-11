<?php

///////////////////////////////////////////////////////////////////
require_once("libs/tools.php");

///////////////////////////////////////////////////////////////////
$videoID = "6Hn8qnsucwo";
//$videoID = "Pr-8AP0To4k";
//$videoID = "yLAjKtmT3lk";
$text = "I DID IT !";
$from = 30;
$duration = 5;
$width = 400;
$yOffset = 0;
$xOffset = 0;
$fontSize = 35;
$color = "white";
$borderColor = "black";

//////////////////////////////////////////////////////////////////
$fontFile = "assets/impact.ttf";
$gifFile = "data/".$videoID.".gif";
$gifFileFinal = "data/".$videoID."-final.gif";

///////////////////////////////////////////////////////////////////
$result = run("node","libs/youtubeToGif/youtubeToGif.js",$videoID,$width,$from,$duration);
if($result["code"] != 0) {
    die("youtube to gif failed");
}
$gif = new Imagick($gifFile);
$draw = new ImagickDraw();
$draw->setGravity (Imagick::GRAVITY_CENTER);
$draw->setStrokeColor($borderColor);
$draw->setStrokeWidth(2);
$draw->setStrokeAntialias(true);
$draw->setTextAntialias(true); 
$draw->setFont($fontFile);
$draw->setFontSize($fontSize);
$draw->setFillColor($color);
foreach($gif as $frame) {
    list($lines, $lineHeight) = wordWrapAnnotation($gif, $draw, $text, $width - 20);
    $lines = array_reverse($lines);
    for($i = 0; $i < count($lines); $i++) {
        $gif->annotateImage($draw, $xOffset, $yOffset+$i*$lineHeight, 0, $lines[$i]);
    }
}    
$gif->writeImages($gifFileFinal,true);

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
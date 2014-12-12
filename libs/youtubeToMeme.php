<?php

///////////////////////////////////////////////////////////////////
namespace youtubeToMeme;
define("POSITION_TOP", 2);
define("POSITION_BOTTOM", 8);

///////////////////////////////////////////////////////////////////
require_once("tools.php");

class youtubeToMeme
{
    ///////////////////////////////////////////////////////////////////
    private $fontFile;
    private $dataFolder;
    private $fontSize = 40;
    private $color = "white";
    private $borderColor = "black";
    private $yOffset = 10;
    private $xOffset = 0;
    private $position = POSITION_TOP;

    ///////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->fontFile = __DIR__."/../assets/impact.ttf";
        $this->dataFolder = __DIR__."/../data";
    }

    ///////////////////////////////////////////////////////////////////
    public function makeMeme($videoID, $from, $duration, $text, $width) {
        
        $gifFile = $this->dataFolder."/".$videoID.".gif";
        $gifFileFinal = $this->dataFolder."/".$videoID."-final.gif";
        
        $result = run("node","libs/youtubeToGif/youtubeToGif.js",$videoID,$width,$from,$duration);
        if($result["code"] != 0) {
            return null;
        }

        $gif = new \Imagick($gifFile);
        $draw = new \ImagickDraw();
        $draw->setGravity($this->position);
        $draw->setStrokeColor($this->borderColor);
        $draw->setStrokeWidth(2);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true); 
        $draw->setFont($this->fontFile);
        $draw->setFontSize($this->fontSize);
        $draw->setFillColor($this->color);
        foreach($gif as $frame) {
            list($lines, $lineHeight) = $this->wordWrapAnnotation($gif, $draw, $text, $width - 20);
            if($this->position == POSITION_BOTTOM) {
                $lines = array_reverse($lines);
            }
            for($i = 0; $i < count($lines); $i++) {
                $gif->annotateImage($draw, $this->xOffset, $this->yOffset+$i*$lineHeight, 0, $lines[$i]);
            }
        }    
        $gif->writeImages($gifFileFinal,true);
        return $gifFileFinal;
    }

    ///////////////////////////////////////////////////////////////////
    private function wordWrapAnnotation(&$image, &$draw, $text, $maxWidth)
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
}

?>
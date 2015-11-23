<?php

///////////////////////////////////////////////////////////////////
namespace youtubeToMeme;
require_once(__DIR__."/../config.php");
define("youtubeToMeme\POSITION_TOP", 2);
define("youtubeToMeme\POSITION_BOTTOM", 8);

///////////////////////////////////////////////////////////////////
require_once("tools.php");

class youtubeToMeme
{
    ///////////////////////////////////////////////////////////////////
    private $fontFile;
    private $dataFolder;
    private $fontSize = 30;
    private $color = "white";
    private $borderColor = "black";
    private $yOffset = 10;
    private $xOffset = 0;

    ///////////////////////////////////////////////////////////////////
    public function __construct() {
        $this->fontFile = ASSETS_DIR."/impact.ttf";
        $this->dataFolder = DATA_DIR;
    }

    ///////////////////////////////////////////////////////////////////
    public function makeMeme($videoID, $from, $duration, $text, $width, $position) {
        
        $gifFile = $this->dataFolder."/".$videoID.".gif";
        $gifFileFinal = $this->dataFolder."/".$videoID."-final.gif";
        
        $result = run("node","libs/youtubeToGif/youtubeToGif.js",$videoID,$width,$from,$duration);
        if($result["code"] != 0) {
            return array("success"=>false,"hint"=>dump($result["output"]));
        }

        $gif = new \Imagick($gifFile);
        $draw = new \ImagickDraw();
        $draw->setGravity($position);
        $draw->setStrokeColor($this->borderColor);
        $draw->setStrokeWidth(2);
        $draw->setStrokeAntialias(true);
        $draw->setTextAntialias(true); 
        $draw->setFont($this->fontFile);
        $draw->setFontSize($this->fontSize);
        $draw->setFillColor($this->color);
        foreach($gif as $frame) {
            list($lines, $lineHeight) = $this->wordWrapAnnotation($gif, $draw, $text, $width - 20);
            if($position == POSITION_BOTTOM) {
                $lines = array_reverse($lines);
            }
            for($i = 0; $i < count($lines); $i++) {
                $gif->annotateImage($draw, $this->xOffset, $this->yOffset+$i*$lineHeight, 0, $lines[$i]);
            }
        }    
        $gif->writeImages($gifFileFinal,true);
        return array("success"=>true,"file"=>$gifFileFinal);
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
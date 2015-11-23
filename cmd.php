<?php

///////////////////////////////////////////////////////////////////
require_once(__DIR__."/libs/youtubeToMeme.php");

///////////////////////////////////////////////////////////////////
if(count($argv) != 7) die("bad argumenrs : VIDEO_ID FROM DURATION \"TEXT\" WIDTH POSITION[TOP|BOTTOM]");

$ytm = new youtubeToMeme\youtubeToMeme();
$position = strtoupper($argv[6]) == "TOP" ? youtubeToMeme\POSITION_TOP : youtubeToMeme\POSITION_BOTTOM;
$result = $ytm->makeMeme($argv[1],$argv[2] ,$argv[3], $argv[4], $argv[5], $position);
if(!$result["success"]) die("failed ! : ".$result["hint"]);
else echo "gif is available here : ".$result["file"];

?>
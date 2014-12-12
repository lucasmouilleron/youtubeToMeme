<?php

///////////////////////////////////////////////////////////////////
require_once("libs/youtubeToMeme.php");

///////////////////////////////////////////////////////////////////
$ytm = new youtubeToMeme\youtubeToMeme();

//$meme = ["6Hn8qnsucwo",30,5, "PRETTY ... PRETTY ... PRETTY GOOD !"];
//$meme = ["_tj5ye2r8RI",30,5, "PRETTY ... PRETTY ... PRETTY GOOD !"];
//$meme = ["yLAjKtmT3lk",30,5, "PRETTY ... PRETTY ... PRETTY ... PRETTY ... PRETTY GOOD !"];
//$meme = ["eL2I5GY_Vvs",166 ,5, "T'ES SERIEUX GRENOBLE"];
//$meme = ["vuR1Aous22o",130 ,5, "SANDRA WTF ???"];
//$meme = ["xaZK_2m3NjU",180 ,2, "CAN'T WAIT FOR THE NEXT ONE"];
//$meme = ["QJbZ-IT1gU8",21 ,5, "T'ES SERIEUX LE DRIFT"];
//$meme = ["-onuvbdl23Q",64 ,4, "SOUVENIR DE SOIREE AVEC TON AMI LUCAS. BISOUS MON MAXIME. TA MAMAN"];
$finalGifFile = $ytm->makeMeme("A7TaY8HWYd8",71 ,5, "PRETTY GOOD JUNIOR", 500);

if($finalGifFile == null) {
    die("failed !");
}
else {
    echo "gif is available here : ".$finalGifFile;
}

?>
<?php

////////////////////////////////////////////////////////////////
function run($command) {
    $output = array();
    $code = -1;
    $args = func_get_args();
    if(count($args)>1) $command = implode(" ",$args);
    $command.=" 2>&1";
    ob_start();
    $moreOutput = exec($command,$output,$code);
    $moremoreoutput = ob_get_clean();
    $ouput[]=$moreOutput;
    $ouput[]=$moremoreoutput;
    return array("code"=>$code,"output"=>$output,"success"=>($code==0));
}

////////////////////////////////////////////////////////////////
function dump($var) {
    return var_export($var,true);
}

?>
<?php

function fillArray($fill, $filled){
    $a = [];
    foreach($filled as $k => $v){
        if(gettype($v) == 'array'){
            $a[$k] = fillArray($fill[$k],$v);
        }else{
            $a[$k] = $fill[$k];
        }
    }
    return $a;
}

function switchKeys($array){
    $a = [];
    foreach($array as $k => $v){
        $a[] = $k;
    }
    return $a;
}

?>

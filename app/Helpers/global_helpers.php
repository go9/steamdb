<?php

function money($value){
    return "$" . number_format($value, 2, '.', ',');
}

function console($value){
    echo "<script>console.log($value);</script>";
}
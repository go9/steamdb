<?php

function money($value){
    return "$" . number_format($value, 2, '.', ',');
}
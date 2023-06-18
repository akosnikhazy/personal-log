<?php
/***********************
	Nikházy Ákos

functions.php - basic functions
***********************/

function jumpTo($target)
{
    header('location:'.$target);
    exit();
}

function vd($toDump,$die = false)
{

    echo '<pre>';
    var_dump($toDump);
    echo '</pre>';

    if($die)die();

}

function lbToP($text,$linebreak = PHP_EOL)
{
    return '<p>' . implode('</p><p>', array_filter(explode($linebreak,$text))) . '</p>';
}

?>
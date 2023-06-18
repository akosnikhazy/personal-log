<?php
/***********************
	Nikházy Ákos

functions.php - basic functions
***********************/

function jumpTo($target)
{ // oldschool with an exit... if the engine older
    header('location:'.$target);
    exit();
}

function vd($toDump,$die = false)
{ // var_dump on steroids for debugging the dumb way, but hey its fast

    echo '<pre>';
    var_dump($toDump);
    echo '</pre>';

    if($die)die();

}

function lbToP($text,$linebreak = PHP_EOL)
{ // this is the only part where you see html in php code. I hate this.
    return '<p>' . implode('</p><p>', array_filter(explode($linebreak,$text))) . '</p>';
}

?>

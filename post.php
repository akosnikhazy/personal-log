<?php
/***********************
	Nikházy Ákos

index.php

you post here
***********************/

require 'require/musthave.php';

if(!isset($_GET['id'])) jumpTo('index.php');

$template = new Template('article');
$inposttaglistitem  = new Template('inposttaglistitem');

$text	  = new Text('index');
$sql = 'SELECT `date`,`text`, `tags` 
		FROM `posts`
		WHERE `id` = ?
		ORDER BY `date` DESC';

$stmt = $db -> prepare($sql);

$stmt -> bind_param('i',$id);

$id = $_GET['id'];

$stmt -> execute();

$res = $stmt -> get_result();

$stmt -> close();

$template -> tagList['postlist'] = ($res -> num_rows == 0)?$text -> PrintText('nopost'):'';

$obj = $res -> fetch_object();

$template -> tagList['id'] = $id;
$template -> tagList['date'] = $obj -> date;
$template-> tagList['text'] = $obj -> text;

$tags = '';
foreach(explode(',',$obj -> tags) as $tag)
{

	$inposttaglistitem -> tagList['tag'] = $tag;
	
	$tags .= $inposttaglistitem -> Templating();
}

$template -> tagList['taglist'] = $tags;








exit($template -> Templating());
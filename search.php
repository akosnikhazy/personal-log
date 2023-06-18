<?php
/***********************
	Nikházy Ákos

index.php

you post here
***********************/

require 'require/musthave.php';
	
if(!isset($_GET['tag']) && !isset($_GET['text'])) jumpTo('index.php');

$template = new Template('search');
$taglistitem = new Template('taglistitem');
$inposttaglistitem  = new Template('inposttaglistitem');

$post = new Template('post');

$text	  = new Text('index');
/* tag list */

$template -> tagList['taglist'] = '';

$sql = 'SELECT `tag`,`count` 
		FROM `tags`
		ORDER BY `count` DESC'; 

$res = $db -> query($sql);

while($obj = $res->fetch_object())
{
	
	$taglistitem -> tagList['tag'] = $obj -> tag;
	$taglistitem -> tagList['count'] = $obj -> count;
	
	$template -> tagList['taglist'] .= $taglistitem -> Templating();
}



/* search */

$where = '';

/* sorry, lazy and only support one type os search at once */
if(isset($_GET['tag'])) $where = '`tags` LIKE ?';
if(isset($_GET['text'])) $where = '`text` LIKE ?';

$sql = 'SELECT `id`,`date`,LEFT(`text`, 250) AS `shorttext`, `tags` 
		FROM `posts`
		WHERE '. $where . '
		ORDER BY `date` DESC';

$stmt = $db -> prepare($sql);

$stmt -> bind_param('s',$search);

foreach($_GET as $val)
{
	$search = "%" . $val . "%";
}

$stmt -> execute();

$res = $stmt -> get_result();

$stmt -> close();

$template -> tagList['postlist'] = ($res -> num_rows == 0)?$text -> PrintText('noposts'):'';

while($obj = $res -> fetch_object())
{
	$post -> tagList['id'] = $obj -> id;
	$post -> tagList['date'] = $obj -> date;
	$post -> tagList['text'] = $obj -> shorttext . $text -> PrintText('dots');

	$tags = '';
	foreach(explode(',',$obj -> tags) as $tag)
	{

		$inposttaglistitem -> tagList['tag'] = $tag;
		
		$tags .= $inposttaglistitem -> Templating();
	}
	
	$post -> tagList['taglist'] = $tags;
	
	$template -> tagList['postlist'] .= $post -> Templating();

}




exit($template -> Templating());
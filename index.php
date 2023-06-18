<?php
/***********************
	Nikházy Ákos

index.php

you post here
***********************/

require 'require/musthave.php';

if(isset($_POST['post']))
{

	/* enters are paragraphs */
	$paragraphs = lbToP($_POST['post']);
	
	/* too much enters are filtered */
	$paragraphs = str_replace('<p></p>','',$paragraphs);

	/* saving to db */
	$stmt = $db -> prepare('INSERT INTO `posts` (`text`,`tags`)
							VALUES (?,?)');

	$stmt -> bind_param('ss',$text,$tags);

	$text = $paragraphs;
	$tags = ($_POST['tags'] != '')?mb_strtolower($_POST['tags']):NULL;

	
	$stmt -> execute();

	$postid = $db->insert_id;
	
	$stmt -> close();

	/* saving tags if there is any*/
	if($tags != '')
	{
		$tags = explode(',',$_POST['tags']);
		
		$sql = 'INSERT INTO tags (`tag`,`count`) VALUES ';
		$values = array();
		foreach($tags as $tag)
		{
			$values[] = '("' . trim($tag) . '", 1)';
		}
		$sql .= implode(',',$values);

		
		$sql .= ' ON DUPLICATE KEY UPDATE `count` = `count` + 1';

		$db -> query($sql);

	}

	jumpTo('post.php?id=' . $postid);

	
}

$template = new Template('index');
$taglistitem = new Template('taglistitem');
$inposttaglistitem  = new Template('inposttaglistitem');
$yearlistitem = new Template('yearlistitem');
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

/* years */

$template -> tagList['yearlist'] = '';

$sql = 'SELECT YEAR(MIN(`date`)) AS `old`, YEAR(MAX(`date`)) AS `fresh` FROM `posts`';

$res = $db -> query($sql) -> fetch_object();

if($res != NULL)
{
	foreach(range($res -> fresh,$res -> old) as $year)
	{

		$yearlistitem -> tagList['year'] = $year;
		
		$template -> tagList['yearlist'] .= $yearlistitem -> Templating();

	}
}

/* post from selected year */

$year = date('Y');


if(isset($_GET['year'])) $year = $_GET['year'];

$sql = 'SELECT `id`,`date`,LEFT(`text`, 250) AS `shorttext`, `tags` 
		FROM `posts`
		WHERE YEAR(`date`) = ?
		ORDER BY `date` DESC';

$stmt = $db -> prepare($sql);

$stmt -> bind_param('i',$year);

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
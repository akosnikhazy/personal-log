<?php
/***********************
	Nikházy Ákos

login.php

***********************/
// lazything if you want to make your own hash with salt just echo it and copy it in the pw file ¯\_(ツ)_/¯

// die(hash('sha256','admin' . '4b21bea153fb8dfe3fc53eb9b94cd463dc5cfcd8718bdc423a4f1afc6eacaaf3'));

require 'require/musthave.php';




if(isset($_SESSION['login'])) jumpTo('index.php');

if(isset($_POST['openthelogplease']))
{
	$pwxsalt = explode('x',file_get_contents('pw'));
	
	/* you might want to come up with your own hashing */
	if($pwxsalt[0] == hash('sha256',$_POST['openthelogplease'] . $pwxsalt[1]))
	{
		
		$_SESSION['login'] = 1;
		
		$logThis -> LogThis(1);

		jumpTo('index.php');

	} 

	$logThis -> LogThis(0);
	exit('nah');

}

$template = new Template('login');

exit($template -> Templating());
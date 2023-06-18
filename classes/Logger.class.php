<?php
class Logger {

    private $db;

	function __construct($_db) 
	{
        $this -> db = $_db;
    }

    public function LogThis($event)
    {
       

        $sql = 'INSERT INTO `accesslog` (`ip`,`event`) 
                VALUES ("' . $_SERVER['REMOTE_ADDR'] . '",' . $event . ')';

        $this -> db -> query($sql);

    }
}
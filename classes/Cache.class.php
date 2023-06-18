<?php
/***********************
	Nikházy Ákos

Cache.class.php
***********************/
class Cache {
	
	private $url;
	private $cacheTime;
	private $cacheFile;
	
	public $cacheFolder = 'cache';
	
	function __construct($_time = 60*2) 
	{
		$this->url 			=  $this->getURL();
		
		// here you can change the file name. For examplye you can add "username-"
		// to its beginning if you want to cache per user basis. If you change
		// I simply md5 the file name, but you could use simply file name or any
		// other identifier too.
		$this->cacheFile 	= $this->cacheFolder .'/' . md5($this->url) . '.cache';
		$this->cacheTime	= $_time; 
		
		if (file_exists($this->cacheFile) && filemtime($this->cacheFile) > (time() - $this->cacheTime))
			$this->exitHere();
		
		$this->cacheDelete();
	}
	
	
	public function cache($contents)
	{// you cahce the generated content here
		if(!error_get_last())
			file_put_contents($this->cacheFile, $contents, LOCK_EX);
		
		$this->exitHere($contents);

	}
	
	private function exitHere($contents = NULL)
	{
		header('Content-Type: text/html; charset=utf-8');
		
		if($contents != NULL) exit($contents);
		
		exit(file_get_contents($this->cacheFile));
	}
	
	private function cacheDelete()
	{
		if(file_exists('/' . $this->cacheFolder . '/' . $this->cacheFile))
		{
			$old = getcwd(); 
			chdir($this->cacheFolder);
			unlink($this->cacheFile);
			chdir($old);
		}
	}
	
	private function getURL () 
	{
		if (!isset($_SERVER['REQUEST_URI']))
			return $_SERVER['REQUEST_URI'];
				
		return $_SERVER['SCRIPT_NAME'] . ((!empty($_SERVER['QUERY_STRING']))? '?' . $_SERVER['QUERY_STRING']:'');	
	}
	
}
?>
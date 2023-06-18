<?php
/***********************
	Nikházy Ákos

Text.class.php loadin text from .json 
***********************/

class Text{
	

	public  $textFolder		= 'text';
	
	private $textFile = '';
	
	
	function __construct($_fileName) 
	{
	
		$this->textFile	= json_decode (file_get_contents($this->textFolder . '/' . $_fileName .  '.json'),true);
		
	}
	
	public function getTextArray()
	{
		return $this->textFile;
	}
	
	
	public function PrintText($id)
	{
		
		if(array_key_exists($id,$this->textFile))
			return $this->textFile[$id];

		return 'missing text';
		
		
	}

}
?>

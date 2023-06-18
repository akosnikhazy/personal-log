<?php
/***********************
	Nikházy Ákos

Template.class.php

Usage:
$template = new Template('templateName','a string with {{replacable stuff}} in it'); // template name is the file name of the html file in the templates folder

$template -> tagList['tag1name'] = 'tag 1 content';
$template -> tagList['tag2name'] = 'tag 2 content';
$template -> tagList['tag3name'] = NULL; // if its NULL it loads a HTML file content itself. In this case: tag3name.html 
					 // it comes handy when you have simple repeating HTML tags you want put in your result.
					 
					 // Be aware: if you use data from database, you can end up with NULL as value. You should
					 // always check for that case if a tag is expected to have real content. You can do this:
					 //  $template -> tagList['content'] = ($data === NULL)? 'no data' : $data;

echo $template -> Templating();       // this returns the finished templated file content (returning and one liner code is true by default)
$template -> Templating(false);        // this collects the finished templated content in the finishedTemplate property
$template -> Templating(false,false);  // this collects the finished templated content in the finishedTemplate property AND it doesn't force the HTML in one line

Example template file:

templateName.html

<span>{{tag1name}}</span>
<div id="{{tag2name}}"></div>

***********************/

class Template{
	
	private $fileName 		= '';
	private $rawString 		= '';
	private $templateFile	        = NULL;
	private $fromWhat		= Array();
	private $toWhat			= Array();
	
	// you collect content in this like this: $object -> tagList['tagName'] = 'stg';
	public $tagList 		= Array();
	
	// you can customize the folder. For example I had a case where I had XML templates too so I made a templates/XML folder too
	public $templateFolder	= 'templates/html';
	
	// if you want something else than html
	public $templateFileExtension = 'html';
		
	// you can customize your tags now its {{tagname}}
	public $tagOpen			= '{{';
	public $tagClose		= '}}';
	
	// you can access the templated content
	public $finishedTemplate	= '';
	public $finishedString		= '';
	

	
	function __construct($_fileName,$_rawString = '') 
	{
		// ****************
		// $_fileName: template file's name without extension. Setup extension with $templateFileExtension
		//   			use NULL when you want to use $_rawString
		// 
		// $_rawString: template a string instead of file. If its NULL it assumes file
		// ****************
		
		$this -> fileName 		= $_fileName;
		$this -> rawString  	= $_rawString;
		if($_fileName !== '') // this happens when you use raw sting mode instead
			$this -> templateFile	= $this -> getTemplateFile($_fileName);
		
	}
	
	public function Templating($return = true,$oneLiner = true)
	{
		
		// ****************
		// $return: if false it will only save the templateted HTML in finishedTemplate property
		// 
		// $oneLiner: if false it returns the HTML as is. 
		// ****************
		
		$this -> fromWhat	= Array();
		$this -> toWhat		= Array();
			
		foreach($this -> tagList as $tag => $content)
		{
			
			$this -> fromWhat[]	= $this -> tagOpen . $tag . $this -> tagClose;
			$this -> toWhat[] 	= $content;
			
			if($content === NULL) // rare case
				$this -> toWhat[count($this -> toWhat)-1] = $this -> getTemplateFile($tag);
			
		}
		
		$this -> finishedTemplate = str_replace($this -> fromWhat,$this -> toWhat,$this -> templateFile);
		$this -> finishedString   = str_replace($this -> fromWhat,$this -> toWhat,$this -> rawString);
		
		if($return)
		{ 
			if($this -> rawString === '')
				return ($oneLiner)?$this -> oneLiner($this -> finishedTemplate)
								  :$this -> finishedTemplate;
		
			return ($oneLiner)?$this -> oneLiner($this -> finishedString)
							  :$this -> finishedString;
		}
		
	}
	
	private function getTemplateFile($fileName)
	{
		// ****************
		// $fileName: name of the file.
		// ****************
		
		if(!file_exists($this -> templateFolder . '/' . $fileName . '.' . $this -> templateFileExtension))
			return '';
		
		return file_get_contents($this -> templateFolder . '/' . $fileName . '.' . $this -> templateFileExtension);
			
		
	}
	
	public function oneLiner($in)
	{// made it public, you might want to use it elsewhere
		return preg_replace('/^\s+|\n|\r|\t|\s+$/m', '', $in);
	}
}
?>

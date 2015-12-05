<?php namespace IntegrationTest\Pages\Widgets;

use \IntegrationTest\Lib\WebDriver;
class Deal{

	private $element;
	
	public function __construct(\RemoteWebElement $element){
		$this->element = $element;
	}
	
	
	
	public function click(){
		// normal click not working. Use mouse hover and click
		WebDriver::instance()->action()
            ->moveToElement($this->element)
            ->clickAndHold()
            ->release()
            ->perform();
	}
	
	public function isVisible(){
		//TODO
	}
	
	
}

<?php namespace IntegrationTest\Pages\Widgets;

use \IntegrationTest\Lib\Util;

class Suggestions{
	
	const SUGGESTED_CITY_SELECTOR = "li.js-ssg-suggestion";
	private $element;
	
	public function __construct(\RemoteWebElement $element){
		$this->element = $element;
	}
	
	
	public function suggestedCityCount(){
		return count($this->element->findElements(\WebDriverBy::cssSelector(self::SUGGESTED_CITY_SELECTOR)));
	}

	public function getSuggestedCities(){
		$els = $this->element->findElements(\WebDriverBy::cssSelector(self::SUGGESTED_CITY_SELECTOR));
		$cities = [];
		foreach($els as $el){
			$cities[] = new City($el);
		}
		
		return $cities;
	}
	
}

class City{
	private $element;
	const TITLE_SELECTOR = "span.ssg-title";
	const SUB_TITLE_SELECTOR = "span.ssg-subtitle";
	
	public function __construct(\RemoteWebElement $element){
		$this->element = $element;
	}
	
	public function click(){
		$this->element->click();
	}
	
	
	/*
	 * return title of suggested option
	 */
	
	public function getName(){
		return $this->element->findElement(\WebDriverBy::cssSelector(self::TITLE_SELECTOR)->getText());
	}
	
	/*
	 * return true if given text present in suggested option
	 */
	public function hasText($text){
		return Util::hasString($this->element->getText(), $text, true);		
	}
	
	
}

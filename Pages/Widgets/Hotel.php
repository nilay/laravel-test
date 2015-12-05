<?php namespace IntegrationTest\Pages\Widgets;

use \IntegrationTest\Pages\Widgets\Deal;
class Hotel{
	const VIEW_DEALS_SELECTOR = "ul.item_nav span.label";
	const DEALS_SELECTOR = "div.deal_wrp";
	const HOTEL_NAME_SELECTOR = "div.item_prices span.item_name";
	private $element;
	
	public function __construct(\RemoteWebElement $element){
		$this->element = $element;
	}
	
	
	public function viewAllDealsClick(){
		$this->element->findElement(\WebDriverBy::cssSelector(self::VIEW_DEALS_SELECTOR))->click();
	}
	
	public function getName(){
		return $this->element->findElement(\WebDriverBy::cssSelector(self::HOTEL_NAME_SELECTOR))->getText();
	}
	
	public function getAllDeals(){
    	$dealsElements = $this->element->findElements(\WebDriverBy::cssSelector(self::DEALS_SELECTOR));
    	$deals = [];
    	foreach($dealsElements as $element){
    		$deals[] = new Deal($element);
    	}
    	
    	return $deals;
		
	}
	
	public function waitUntilDealsIsLoaded($maxWaitSeconds = 10){
        $seconds = 0;
        do{ 
    		sleep(1);
        	$seconds++;
        }
	    while(count($this->getAllDeals()) < 1 and $seconds < $maxWaitSeconds);
    }
	
	
}

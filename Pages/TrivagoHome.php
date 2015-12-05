<?php namespace IntegrationTest\Pages;

use IntegrationTest\Config\Config;
use IntegrationTest\Lib\WebDriver;
use IntegrationTest\Lib\Helper;
use IntegrationTest\Pages\Widgets\Grid\MixGrid;
use IntegrationTest\Pages\Widgets\Suggestions;
use IntegrationTest\Pages\Widgets\Hotel;

class TrivagoHome extends \IntegrationTest\Lib\PageObject{
	
	
    public static  $elements = [
    	"form"=>["cssSelector"=>"#js_dealform_querycenter"], 
        "searchBox"=>["cssSelector"=>"#js_querystring", "extends"=>"form"],
        "suggestions"=>["cssSelector"=>"ul.js-ssg-suggestions", "extends"=>"form"],
        "calendarPopup"=>["cssSelector"=>"div.js_df_overlay", "extends"=>"form"],
        "calendarClose"=>["cssSelector"=>"span.img_sprite_moon", "extends"=>"calendarPopup"],
        "hotelList"=>["cssSelector"=>"ul#js_itemlist"]
    ];
    
    
    static function getSuggestionBox(){
    	return new Suggestions(self::getElement("suggestions"));
    }
    
    static function closeCalendar(){
    	self::getElement("calendarClose")->click();
    }
    
    static function getMatchingOptionFromSuggestionDropdown($cityName, $countryName){
        $suggestionBox = TrivagoHome::getSuggestionBox();
        $suggestedCitites = $suggestionBox->getSuggestedCities();
        $matchingCity = null;
        foreach($suggestedCitites as $city){
        	if($city->hasText($cityName) && $city->hasText($countryName)){
        		$matchingCity = $city;
        		break;
        	}
        }
    	
    	return $matchingCity;
    }
    
    static function getHotels(){
    	$hotelsElements = self::getElement("hotelList")->findElements(\WebDriverBy::cssSelector("li.hotel"));
    	$hotels = [];
    	foreach($hotelsElements as $element){
    		$hotels[] = new Hotel($element);
    	}
    	
    	return $hotels;
    }
    
    static function waitUntilHotelListIsLoaded($maxWaitSeconds = 10){
        $seconds = 0;
        do{ 
    		sleep(1);
        	$seconds++;
        }
	    while(count(self::getHotels()) < 1 and $seconds < $maxWaitSeconds);
    	
    }
    
}

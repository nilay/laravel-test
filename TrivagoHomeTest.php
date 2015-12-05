<?php
/*************************************************************************************************
 * 1. Visit the website and search for the German city "Hamburg". !
 * 2. Get the name of the second hotel of the result list.!
 * 3. Have a look at all available deals and choose the third deal.!
 * 4. Verify that the hotel name of the second hotel in our result list is available on the partners
 * website (a partners website is the final website after you had clicked on View Deal, e.g. from booking.com or Expedia).!
 * On the second page you will find some screenshots.!
 * 
 * *
 * The rules!
 *
 * - The search suggestions of the input field have to be used!
 * - The calendar of the search form has to be closed after the search was executed! You have to choose the second hotel in the result list!
 * - You have to chose the third price of the View all deals window!
 * - All the best. Some more text here 
 ********************************************************************************************************/

use IntegrationTest\Config\Config;
use IntegrationTest\Lib\BaseTest;
use IntegrationTest\Lib\WebDriver;
use IntegrationTest\Lib\Helper;
use IntegrationTest\Lib\Assert;
use IntegrationTest\Lib\Util;
use IntegrationTest\Pages\Windows\Window;

use IntegrationTest\Lib\Exceptions\UndefinedEnvironmentParamException;
use IntegrationTest\Pages\TrivagoHome;

class TrivagoHomeTest extends BaseTest {
	const SEARCH_CITY = "Hamburg";
	const SERCH_COUNTRY = "Germany";
	
    public function setUp($browser=null){
        parent::setUp();
    }
    
    public function testBooking(){
        WebDriver::instance()->get("http://trivago.com");
        Helper::waitUntilDocumentIsLoaded();
        
        // asset serch box is present and visible on page
        Assert::elementFound(TrivagoHome::getSelector("searchBox"));
        
        // enter search string in search box
        TrivagoHome::getElement("searchBox")->sendKeys(self::SEARCH_CITY);
        
        // wait untill suggestion list appears
        try{
        	Helper::waitUntilVisible(TrivagoHome::getSelector("suggestions"));
        }
        catch( Exception $e){
        	$this->fail("After entering city name in search box, suggestion list did not appear");
        }
        
        
        // suggeation list is visible, pick matching one
        $matchingCity = TrivagoHome::getMatchingOptionFromSuggestionDropdown(self::SEARCH_CITY, self::SERCH_COUNTRY);
        
        
        // assert we see expected option in suggestion list
        $this->assertFalse($matchingCity==null, "Expected option Hamburg (Germany) do not appear in suggestion list");
        
        
        // we have matching city in option list
        $matchingCity->click();
        
        // lets calender be visible
        sleep(1);
        $this->assertTrue(TrivagoHome::getElement("calendarPopup")->isDisplayed(), "After clicking on city from suggestion list, Calendar did not appear");
        
        // close calendar in order to see the hotel list
        TrivagoHome::closeCalendar();
        
        // hotel list is reloaded after intial load. hence wait 
        // TODO: figure out better way to wait than just sleep for harcoded time
        sleep(6);
        
        TrivagoHome::waitUntilHotelListIsLoaded();
        // collect all hotels from search list
        $hotels = TrivagoHome::getHotels();
        
        // make sure we have sufficient hotels in list
        $this->assertTrue(count($hotels) > 2, "Not sufficient hotels found in list");
        
        // as per test case, we need to pick second hotels from list
        // scroll page up before clicking on view all deals link
        Helper::scrollPageToBottomByPixel(300); 
        $hotels[1]->viewAllDealsClick();
        $hotels[1]->waitUntilDealsIsLoaded();
        $hotelName = $hotels[1]->getName();
        
        // deals are visible now
        $deals = $hotels[1]->getAllDeals();
        
        // make sure we have few deals in list
        $this->assertTrue(count($deals) > 2, "Not sufficient deals found in list");
        
        // as per test, click on third deal
        $deals[2]->click();
        
        Helper::waitUntilPopupUrlContains('www.trivago');
        // lets third party website load its content
        sleep(2);
        $window = new Window();
        $this->assertTrue(Util::hasString($window->getText(), $hotelName, true), "Expected hotel name '$hotelName' do not found on partner website");
        $window->close();
    }
    
}

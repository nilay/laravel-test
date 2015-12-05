<?php namespace IntegrationTest\Lib;

use IntegrationTest\Config\Config;
use \IntegrationTest\Lib\WebDriver;
use \IntegrationTest\Lib\Assert;
use \IntegrationTest\Lib\Util;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {
    
   public static function setUpBeforeClass(){        
        // On jenkins server, kill firefox instance if it has been left in memory by previous test
        if(file_exists('/usr/local/bin/killfox'))
            // custom lib
            exec("killfox");
   }
   
      
    public function setUp($browser=null){
    	
        // put logger off
    	
    	// assign default browser if it is not specified
    	if($browser === null){
    		$browser = Config::get("SELENIUM_WEBDRIVER");
    	} 
    	
    	// start webdriver
    	WebDriver::start($browser);
    	
    	// assign 'this' to Assert class 
    	Assert::assignCallerObject($this);

        if(Config::get("MAKE_BROWSER_MAXIMIZE")){
        	//WebDriver::instance()->manage()->window()->maximize() ;		// not working with headless browser on Linux build server
        	WebDriver::instance()->manage()->window()->setSize(new \WebDriverDimension(1920, 1080));
        }
    }
    
    public function resetWebDriver(){
        $this->tearDown();
        $this->setUp();
    }
            
    public function tearDown(){
    	$reflection = print_r(WebDriver::instance(), true);
    	
    	// quit browser instance if it is not already
        if( Util::hasString($reflection, "HttpCommandExecutor Object")){
        	WebDriver::instance()->quit();
        }
    }
    
    static function tearDownAfterClass() {
        // called after the last test of the test case class is run
    }
        
}
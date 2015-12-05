<?php namespace IntegrationTest\Lib;

use \IntegrationTest\Lib\WebDriver;
class Assert {
	
	static $unitTest = null;
	
	/**
	 * assign PHPUnit_Framework_TestCase object which is used to mark the test pass/fail
	 */
	static function assignCallerObject(\PHPUnit_Framework_TestCase $unitTest){
		self::$unitTest = $unitTest;
	}
	
	/**
	 * Mark test fail if given element present in DOM
	 */
    static function elementNotFound(\WebDriverBy $by){
        $els = WebDriver::instance()->findElements($by);
        if (count($els)) {
            self::$unitTest->fail('Unexpectedly element {"method":"' . $by->getMechanism() . '", "selector":"' . $by->getValue() . '"} was found' );
        }
        // increment assertion counter
        self::$unitTest->assertTrue(true);        
    }

	/**
	 * Mark test fail if given element not present in DOM
	 */
    static function elementFound(\WebDriverBy $by){
        $els = WebDriver::instance()->findElements($by);
        if (!count($els)) {
            self::$unitTest->fail('Unexpectedly element {"method":"' . $by->getMechanism() . '", "selector":"' . $by->getValue() . '"} was not found' );
        }
        // increment assertion counter
        self::$unitTest->assertTrue(true);        
    }

	/**
	 * Mark test fail if given element is not visible on page
	 */
    static function elementVisible(\WebDriverBy $by){
        $els = WebDriver::instance()->findElements($by);
        if (!count($els)) {
            self::$unitTest->fail('Unexpectedly element {"method":"' . $by->getMechanism() . '", "selector":"' . $by->getValue() . '"} was neither found present nor visible' );
        }
        
        if (!$els[0]->isDisplayed()) {
        	self::$unitTest->fail('Unexpectedly element {"method":"' . $by->getMechanism() . '", "selector":"' . $by->getValue() . '"} was not found visible' );
        }
        // increment assertion counter
        self::$unitTest->assertTrue(true);        
    }

	/**
	 * Mark test fail if given element is visible on page
	 */
    static function elementNotVisible(\WebDriverBy $by){
        $els = WebDriver::instance()->findElements($by);
        if (!count($els)) {
			// given element not present in DOM means not visible
			self::$unitTest->assertTrue(true);
			return;
        }
        
        if ($els[0]->isDisplayed()) {
        	self::$unitTest->fail('Unexpectedly element {"method":"' . $by->getMechanism() . '", "selector":"' . $by->getValue() . '"} was found visible' );
        }
        // increment assertion counter
        self::$unitTest->assertTrue(true);        
    }
    
    
    static function at($url, $title=null){
    	self::$unitTest->assertEquals(trim(WebDriver::instance()->getCurrentUrl(),"/"), trim($url, "/"));
    	
    	if($title){
    		self::$unitTest->assertEquals(WebDriver::instance()->getTitle(), $title);
    	}
    }
	
}
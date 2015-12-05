<?php namespace IntegrationTest\Lib;

use IntegrationTest\Lib\Helper;
class PageObject {
	
	const DEFAULT_ATTRIBUTE = "cssSelector";
	static $pageUrl;
	static $lastError = null;
	
	static function init($url){
		self::$pageUrl = $url;
	}

    static function go(){
    	if(!self::$pageUrl){
    		throw new \Exception("Page object class is not initialized with page url");
    	}
    	WebDriver::instance()->get(self::$pageUrl);
    }

    static function isAt(){
    	if(!self::$pageUrl){
    		throw new \Exception("Page object class is not initialized with page url");
    	}
    	return trim('/', WebDriver::instance()->getCurrentUrl()) == trim('/', self::$pageUrl);
    }
	
    static function get($elementKey, $elementsAttribute = self::DEFAULT_ATTRIBUTE){
    	if(!array_key_exists($elementKey,static::$elements)){
    		throw new \Exception("Undefined key '$elementKey'");
    	}
    	
    	if(!array_key_exists($elementsAttribute,static::$elements[$elementKey])){
    		throw new \Exception("Undefined key '$elementsAttribute' for element '$elementKey'");
    	}
    	
    	if(array_key_exists("extends", static::$elements[$elementKey]) and $elementsAttribute == self::DEFAULT_ATTRIBUTE){
    		// Element has extends attribute
    		return self::get(static::$elements[$elementKey]["extends"]). ' ' . static::$elements[$elementKey][$elementsAttribute];
    	}
    	
    	return static::$elements[$elementKey][$elementsAttribute] ;   	
    }
    
    static function getElement($elementName){
    	if(!array_key_exists($elementName, static::$elements )){
    		throw new \Exception("Invalid element name '$elementName'");
    	}
    	return Helper::find(self::get($elementName));
    }

    static function getSelector($elementName){
    	if(!array_key_exists($elementName, static::$elements )){
    		throw new \Exception("Invalid element name '$elementName'");
    	}
    	return \WebDriverBy::cssSelector(self::get($elementName));
    }
    
    static function allExpectedElementsPresent($context=NULL, \RemoteWebElement $parentElement=NULL){
    	foreach(static::$elements as $key=>$element){
    		if(!@$element['onloadPresence']){
    			// no need to check since 'onloadPresence' attribute not dpecified
    			continue;
    		}
    		
    		if(array_key_exists('context', static::$elements[$key]) && $context!==NULL){
    			$elementContexts=explode('|', static::$elements[$key]['context']);
    			if(!in_array($context, $elementContexts)){
	                continue;
	            }
    		}

    		if(array_key_exists('context', static::$elements[$key]) && $context==NULL){
	            // context is there with element but search-context is null
	            continue;
    		}
    		

    		if(!Helper::isElementPresent(\WebDriverBy::cssSelector(self::get($key)), $parentElement)){ 
    			self::setLastError("Element '$key' selector:'" . self::get($key) . "' was not present");
    			return false;
    		}
    	}
    	
    	return true;
    }

    static function allExpectedElementsVisible($context="common"){
    	foreach(static::$elements as $key=>$element){
    		if(!@$element['onloadDisplay']){
    			continue;
    		}
    		
    		if(array_key_exists('context', static::$elements[$key])){
    			$elementContexts=explode('|', static::$elements[$key]['context']);
    			if(!in_array($context, $elementContexts)){
	                continue;
	            }
    		}

    		if(!Helper::isElementVisible(\WebDriverBy::cssSelector(self::get($key)))){ 
    			self::setLastError("Element '" . self::get($key) . "' was not visible");
    			return false;
    		}
    	}
    	
    	return true;
    }
    
    static function setLastError($errorString){
    	self::$lastError = $errorString;
    }
    
    static function getLastError(){
    	return self::$lastError;
    }
    
    
    static function getElementWidth($element){
    	return WebDriver::instance()->findElement(\WebDriverBy::cssSelector(self::get($element)))->getSize()->getWidth();
    }
    
    static function getElementHeight($element){
    	return WebDriver::instance()->findElement(\WebDriverBy::cssSelector(self::get($element)))->getSize()->getHeight();    	
    }

    
    static function getElementX($element){
    	return WebDriver::instance()->findElement(\WebDriverBy::cssSelector(self::get($element)))->getLocation()->getX();    	
    	
    }
    
    static function getElementY($element){
    	return WebDriver::instance()->findElement(\WebDriverBy::cssSelector(self::get($element)))->getLocation()->getY();    	
    	
    }
    
    /**
     * return element horizontal alignment relative to given container or browser screen
     * 
     */
    static function getElementHorizontalAlignment($element, $container=NULL){
    	$alignments = [];
    	if($container){
    		$left = self::getElementX($container);
    		$width = self::getElementWidth($container);
    	}
    	else{
    		// take browser  as container
    		$left = 0;
    		$width = Helper::getBrowserClientWidth();
    	}
    	
    	$center = $width == 0 ? $left : $left + ($width/2);
    	
    	
    	
    	$elementLeft = self::getElementX($element);
    	$elementWidth = self::getElementWidth($element);
    	$elementCenter = $elementWidth == 0 ? $elementLeft : $elementLeft + ($elementWidth/2);
    	
    	if($elementLeft == $left){
    		$alignments[] = 'LEFT';
    	}
    	
    	if(( $elementLeft + $elementWidth) == $width){
    		$alignments[] = 'RIGHT';
    	}
    	
    	if(abs($elementCenter-$center) <=1){
    		$alignments[] = 'CENTER';
    	}
    	
    	return $alignments;    	
    }
    
}

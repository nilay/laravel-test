<?php namespace IntegrationTest\Lib;

use \IntegrationTest\Lib\WebDriver;
use \IntegrationTest\Lib\Util;
use \IntegrationTest\Lib\Exceptions\Timeout; 

class Helper {

    static function isElementPresent(\WebDriverBy $by, \RemoteWebElement $parentElement=NULL){
        if($parentElement){
            $els = $parentElement->findElements($by);
        }
        else{
            $els = WebDriver::instance()->findElements($by);
        }
        return count($els) ? true : false;
    }

    static function isElementVisible(\WebDriverBy $by, \RemoteWebElement $parentElement=NULL){
        if($parentElement){
            $els = $parentElement->findElements($by);
        }
        else{
            $els = WebDriver::instance()->findElements($by);
        }
        return count($els) ? $els[0]->isDisplayed() : false;
    }

    
    static function jsClick(\WebDriverBy $by, \RemoteWebElement $parentElement = null){
        // Many instance, selenium unable to trigger click event on given element
        $element = $parentElement ? $parentElement->findElement($by) : WebDriver::instance()->findElement($by);
        $js = "arguments[0].click();";
        WebDriver::instance()->executeScript($js, array($element));        
    }
    
    /**
     * Scroll page to bring element in viewable port
     */
    static function scrollIntoView(\RemoteWebElement $element){
        $fixedHeaderHeight = 200;
        $y = $element->getLocation()->getY();
        $js = "window.scrollTo(0,($y-$fixedHeaderHeight))";
        WebDriver::instance()->executeScript($js, array());       
    }
    
    static function waitUntilDocumentIsLoaded($waitSeconds = 60){
        $js = 'return document.readyState === "complete"';
        $expireSeconds = 0;
        while( !WebDriver::instance()->executeScript($js) && $expireSeconds < $waitSeconds){
            sleep(1);
            $expireSeconds++;
        }
        
    }
    
    static function waitUntilCurrentUrlIs($url, $waitSeconds = 20){
        $expireSeconds = 0;
        while($expireSeconds < $waitSeconds){
            if(WebDriver::instance()->getCurrentURL() == $url) break;
            sleep(1);
            $expireSeconds++;
        }
        
        // url did not change within given time
        if($expireSeconds >=$waitSeconds){
        	throw new Timeout("Timeout after $waitSeconds seconds. Browser url did not change to expected: $url");
        }
        
    }

    static function waitUntilCurrentUrlContains($string, $waitSeconds = 20){
        $expireSeconds = 0;
        while($expireSeconds < $waitSeconds){
            if(Util::hasString(WebDriver::instance()->getCurrentURL(), $string)) break;
            sleep(1);
            $expireSeconds++;
        }
        
        // url did not change within given time
        if($expireSeconds >=$waitSeconds){
        	throw new Timeout("Timeout after $waitSeconds seconds. Browser url do not have expected sub-string: $string");
        }
        
    }

    static function waitUntilPopupUrlContains($string, $waitSeconds = 20){
        $expireSeconds = 0;
        $windows = WebDriver::instance()->getWindowHandles();
        if(count($windows) < 2){
            throw new \Exception("No popup window found");
        }        
        
        WebDriver::instance()->switchTo()->window($windows[1]);
        while($expireSeconds < $waitSeconds){
            if(Util::hasString(WebDriver::instance()->getCurrentURL(), $string)) break;
            sleep(1);
            $expireSeconds++;
        }
        
        // WebDriver::instance()->switchTo()->window($windows[0]);
        
        if($expireSeconds >= $waitSeconds){
            throw new Timeout("Timeout after $waitSeconds seconds wait. Url does not change from: $string  to something else");
        }
    }


    /**
     * wait untill given element is destroyed
     */    
    static function waitUntillElementDestroyed(\WebDriverBy $by, \RemoteWebElement $parentElement = null, $waitSeconds = 20){
        $expireSeconds = 0;
        while($expireSeconds < $waitSeconds){
            $els = $parentElement ? $parentElement->findElements($by) : WebDriver::instance()->findElements($by);
            if (count($els)== 0) {
                break;
            }
            sleep(1);
            $expireSeconds++;
        }
    }
    
    /**
     * Wait untill any one of given element is visible
     */
     static function waitUntilVisible(\WebDriverBy $elementsBy, \RemoteWebElement $parentElement = NULL, $waitSeconds = MEDIUM_WAIT){
        $expireSeconds = 0;
        $visible = false;
        while($expireSeconds < $waitSeconds){
            
            if(is_array($elementsBy)){
                // multiple elements. wait untill any one of given elements is visible 
                foreach($elementsBy as $by){
                    $els = $parentElement ? $parentElement->findElements($by) : WebDriver::instance()->findElements($by);
                    if (count($els)> 0) {
                        if($els[0]->isDisplayed()){
                            $visible = true;
                            break 2;
                        }
                    }
                }
            }
            else{
                $els = $parentElement ? $parentElement->findElements($elementsBy) : WebDriver::instance()->findElements($elementsBy);
                if (count($els)> 0) {
                    if($els[0]->isDisplayed()){
                        $visible = true;
                        break;
                    }
                }
            
            }
            
            sleep(1);
            $expireSeconds++;
        }
        
        if(!$visible){
            if(is_array($elementsBy)){
                $value = "";
                foreach($elementsBy as $by) $value.=$by->getValue() . ', ';
            }
            else{
                $value=$elementsBy->getValue();
            }
            throw new \Exception("Unexpectedly elements \"$value\" was not found visible after $waitSeconds seconds wait");
        }
     }
     

    /**
     * Wait untill given element is present in DOM
     */
     static function waitUntilPresent(\WebDriverBy $elementsBy, \RemoteWebElement $parentElement = NULL, $waitSeconds = MEDIUM_WAIT){
        $expireSeconds = 0;
        $present = false;
        while($expireSeconds < $waitSeconds){
            $els = $parentElement ? $parentElement->findElements($elementsBy) : WebDriver::instance()->findElements($elementsBy);
            if (count($els)> 0) {
                $present = true;
                break;
            }
            sleep(1);
            $expireSeconds++;
        }
        
        if(!$present){
            $value=$elementsBy->getValue();
            throw new \Exception("Unexpectedly elements \"$value\" was not found present after $waitSeconds seconds wait");
        }
     }
     
    
    
    /**
     * trigger mouse hover and then click
     */
    static function mouseClick(\WebDriverBy $by, \RemoteWebElement $parentElement = null){
        $element = $parentElement ? $parentElement->findElement($by) : WebDriver::instance()->findElement($by);        
        WebDriver::instance()->action()
            ->moveToElement($element)
            ->click()
            ->perform();
        
    }
     
    /**
     * Hover mouse to element
     * mouse down
     * hold
     * then release
     */    
    static function mouseHoldClick(\WebDriverBy $by, \RemoteWebElement $parentElement = null){
        $element = $parentElement ? $parentElement->findElement($by) : WebDriver::instance()->findElement($by);        
        WebDriver::instance()->action()
            ->moveToElement($element)
            ->clickAndHold()
            ->release()
            ->perform();
        
    }

    /**
     * Hover mouse to element
     */    
    static function mouseHover(\WebDriverBy $by, \RemoteWebElement $parentElement = null){
        $element = $parentElement ? $parentElement->findElement($by) : WebDriver::instance()->findElement($by);        
        WebDriver::instance()->action()
            ->moveToElement($element)
            ->perform();        
    }

    /**
     * Hover mouse to \RemoteWebElement
     */    
    static function mouseHoverOnElement(\RemoteWebElement $element){
        WebDriver::instance()->action()
            ->moveToElement($element)
            ->perform();        
    }
    
    /**
     * Shorter version of webDriver->findElement
     */
    static function find($cssSelector, \RemoteWebElement $parentElement = null){
        return $parentElement ? $parentElement->findElement(\WebDriverBy::cssSelector($cssSelector)) : WebDriver::instance()->findElement(\WebDriverBy::cssSelector($cssSelector));                
    }

    static function scrollPageToBottomByPixel($pixel){
       $js = "window.scrollBy(0,$pixel)";
       WebDriver::instance()->executeScript($js, array());        
    }

    static function scrollPageToBottom(){
       $js = "var body = document.body,html=document.documentElement; var height = Math.max( body.scrollHeight,body.offsetHeight,html.clientHeight, html.scrollHeight, html.offsetHeight );window.scrollBy(0,height)";
       WebDriver::instance()->executeScript($js, array());        
    }
    
    static function scrollPageToTop(){
       $js = "var body = document.body,html=document.documentElement; var height = Math.max( body.scrollHeight,body.offsetHeight,html.clientHeight, html.scrollHeight, html.offsetHeight );window.scrollTo(0,0)";
       WebDriver::instance()->executeScript($js, array());        
    }
    
    static function getBrwoserVerticalScrollOffset(){
        $offsetJs = "return function () {
                   var doc = document.documentElement, body = document.body;
                   return (doc && doc.scrollTop  || body && body.scrollTop  || 0);
               }()";
        return WebDriver::instance()->executeScript($offsetJs);
    }
    
    static function getBrowserClientHeight(){
        return WebDriver::instance()->executeScript("return window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;");
    }

    static function getBrowserClientWidth(){
        return WebDriver::instance()->executeScript("return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;");
    }
    
    static function getElementScreenY(\RemoteWebElement $element){
        $js = "
            return arguments[0].offsetTop - (window.pageYOffset || document.documentElement.scrollTop) - - (document.documentElement.clientTop || 0); 
        "; 
                  
        return WebDriver::instance()->executeScript($js, array($element));        
        
    }
    
    static function buildMessage($message){
        $prefix = str_replace(BASE_URL, '', WebDriver::instance()->getCurrentUrl());
        $prefix = $prefix ? $prefix : '/';
        $prefix.= '@' . self::getBrowserClientWidth() . 'x' . self::getBrowserClientHeight();
        $message = str_replace('{{browser_url}}', WebDriver::instance()->getCurrentUrl(), $message );
        $message = str_replace('{{browser_width}}', self::getBrowserClientWidth(), $message );
        $message = str_replace('{{browser_height}}', self::getBrowserClientHeight(), $message );
        return "$prefix: $message";
    }

    static function getBrowserDimensionAsString(){
    	return self::getBrowserClientWidth() . 'x' . self::getBrowserClientHeight();
    }
    
    
}
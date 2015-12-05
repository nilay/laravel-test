<?php namespace IntegrationTest\Pages\Windows;

use \IntegrationTest\Lib\WebDriver;
use \IntegrationTest\Lib\Helper;
use \IntegrationTest\Lib\Util;

class Window{
    public function __construct(){
    
    }
    
    public function waitUntilOpen($waitSeconds = 10){
        $expireSeconds = 0;
        while($expireSeconds < $waitSeconds){
        	$windows = WebDriver::instance()->getWindowHandles();
            if(count($windows) > 1) break;
            sleep(1);
            $expireSeconds++;
        }
    	
    }
    
    public function getText(){
    	return WebDriver::instance()->findElement(\WebDriverBy::cssSelector("body"))->getText();
    }
    
    /**
     * Switch to window
     */
    public function switchTo(){
        $windows = WebDriver::instance()->getWindowHandles();
        if(count($windows) < 2){
            return false;
        }        
        WebDriver::instance()->switchTo()->window($windows[1]);
        return true;    	
    }

    /**
     * Switch to default browser window
     */
    public function switchToDefault(){
        $windows = WebDriver::instance()->getWindowHandles();
        WebDriver::instance()->switchTo()->window($windows[0]);
        return true;        
    }
    
    public function getUrl(){
        if(!$this->switchTo()){
            return null;
        }
        
        $url = WebDriver::instance()->getCurrentUrl();        
        $this->switchToDefault();
        return $url;
    	
    }

    public function isOpen(){
        if(!$this->switchTo()){
        	return false;
        }
        
        $this->switchToDefault();
        return true;            	
    }
    
    public function close(){
        if(!$this->switchTo()){
            return false;
        }
        
        $url = WebDriver::instance()->close();        
        $this->switchToDefault();
        return true;
        
    }
}
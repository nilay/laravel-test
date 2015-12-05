<?php namespace IntegrationTest\Lib;

use IntegrationTest\Config\Config;

class WebDriver{
	
	static $driver = null;
   	static function start($browser){
        $seleniumHost = "http://" . Config::get("SELENIUM_HOST") . ":" . Config::get("SELENIUM_PORT") . "/wd/hub";

        switch($browser){
            case "chrome":
                putenv("webdriver.chrome.driver=" . Config::get("CHROME_WEBDRIVER_PATH"));
                $capabilities = \DesiredCapabilities::chrome();
                $options = new \ChromeOptions();
                $options->addArguments(array("--start-maximized"));
                $options->addArguments(array("applicationCacheEnabled=1"));
                $capabilities->setCapability(\ChromeOptions::CAPABILITY, $options);
                break;
            
            case "ie":
                $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => "Internet Explorer");
                break;
            case "firefox_with_firebug":
		        $profile = new \FirefoxProfile();
		        $profile->setPreference('extensions.firebug.currentVersion', Config::get("FIREBUG_VERSION"));
		        $profile->setPreference("extensions.firebug.allPagesActivation", "on");
		        $profile->setPreference("extensions.firebug.defaultPanelName", "net");
		        $profile->setPreference("extensions.firebug.net.enableSites", true);
		
		        // Set default NetExport preferences
		        $profile->setPreference("extensions.firebug.netexport.alwaysEnableAutoExport", true);
		        $profile->setPreference("extensions.firebug.netexport.defaultLogDir", Config::get("LOG_PATH"));
		        $profile->setPreference("extensions.firebug.netexport.showPreview", false);
		        $profile->setPreference("extensions.firebug.netexport.autoExportToFile", true);
		        $profile->setPreference("extensions.firebug.netexport.autoExportToServer", false);
		        $profile->setPreference("extensions.firebug.netexport.includeResponseBodies", true);
		        
		        //$profile->setPreference("extensions.firebug.netexport.pageLoadedTimeout", 5000);
		        
		        $profile->addExtension(Config::get("FIREBUG_XPI_PATH"))
		            ->addExtension(Config::get("NET_EXPORT_XPI_PATH"));
		            
		        $capabilities = \DesiredCapabilities::firefox();
		        $capabilities->setCapability(\FirefoxDriver::PROFILE, $profile);
                
            	break;
            case "chrome_default_profile":
                putenv("webdriver.chrome.driver=" . Config::get("CHROME_WEBDRIVER_PATH"));
                $capabilities = \DesiredCapabilities::chrome();
                $options = new \ChromeOptions();
                $options->addArguments(array("start-maximized"));
                $options->addArguments(array("applicationCacheEnabled=1"));
                $options->addArguments(array("user-data-dir=". Config::get("BROWSER_DATA_DIR")));
                $capabilities->setCapability(\ChromeOptions::CAPABILITY, $options);
		        
		        break;
			case "firefox_default_profile":
				// TODO: not working correctly. Need fix
            	putenv("webdriver.firefox.profile=default");
                $profile = new \FirefoxProfile();
                $profile->setPreference("webdriver.firefox.profile", "default");
		        //$profile->setPreference("webdriver.load.strategy", "unstable");
		        $capabilities = \DesiredCapabilities::firefox();
		        $capabilities->setCapability(\FirefoxDriver::PROFILE, $profile);
				break; 
            default:
            	// firefox by default
                $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => "firefox");
        }

        self::$driver = \RemoteWebDriver::create($seleniumHost , $capabilities, 90000, 90000);

       
   } 
   
   static function instance(){
   		return self::$driver;
   }
	
	
}

<?php
/**
 * Local config file (not a part of version control)
 * constant define here will overwrite default constant
 * 
 */

// Browser specific data 
define("SELENIUM_WEBDRIVER", "firefox");


// Chrome driver path. change it as per your local machine
defined("CHROME_WEBDRIVER_PATH") || define("CHROME_WEBDRIVER_PATH", "/usr/bin/chromedriver");

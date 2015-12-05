<?php
@include_once dirname(__FILE__) . "/local.php";

defined("BASE_PATH") || define("BASE_PATH", realpath(dirname(__FILE__) . '/..'));
defined("LOG_PATH") || define("LOG_PATH", BASE_PATH .'/build');
// Browser data dir to preserve case and user preference. Machine specific and hence it should be defined in local config 
defined("BROWSER_DATA_DIR") || define("BROWSER_DATA_DIR", BASE_PATH. '/temp_storage');
defined("TRIVAGO") || define("TRIVAGO", "Trivago");


// which browser testsuite will use to run ui tests
// available options: firefox, chrome, ie
defined("SELENIUM_WEBDRIVER") || define("SELENIUM_WEBDRIVER", "firefox");

// flag to make browser maximize before initiating UI test
defined("MAKE_BROWSER_MAXIMIZE") || define("MAKE_BROWSER_MAXIMIZE", false);

/* chromedriver details */
// Default chrome driver path. change it in your local config file if its differ
defined("CHROME_WEBDRIVER_PATH") || define("CHROME_WEBDRIVER_PATH", "/usr/bin/chromedriver");

/* firefox details */
defined("SELENIUM_HOST") || define("SELENIUM_HOST", "localhost");
defined("SELENIUM_PORT") || define("SELENIUM_PORT", "4444");



// change url against where you want to execute your test
// loading http://trivago.com from India redirect to http://trivago.in
// hence we are using .in instead of .com
defined("BASE_URL") || define("BASE_URL", "http://trivago.in");


defined("VERY_LONG_WAIT") || define("VERY_LONG_WAIT", "30");
defined("LONG_WAIT") || define("LONG_WAIT", "20");
defined("MEDIUM_WAIT") || define("MEDIUM_WAIT", "10");
defined("SMALL_WAIT") || define("SMALL_WAIT", "5");

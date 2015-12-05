<?php
namespace IntegrationTest\Lib;
/**
 * Canned ExpectedConditions which are not covered under base WebDriverExpectedCondition class .
 */
class CustomExpectedCondition extends \WebDriverExpectedCondition{
    
    public function __construct($apply){
        parent::__construct($apply);
    }
  
  /**
   * An expectation for checking substring of a page url.
   *
   * @param string $urlPart The expected substring of url.
   * @return CustomExpectedCondition<bool> True when in url,
   *         false otherwise.
   */
  public static function currentUrlContains($urlPart) {
    return new CustomExpectedCondition(
      function ($driver) use ($urlPart) {
        return hasString($driver->getCurrentURL(), $urlPart);
      }
    );
  }
  
  /**
   * An expectation for checking that an element, not known to be present on the DOM
   *
   * @param WebDriverBy $by The element to be checked.
   * @param WebDriverElement $parentElement The element to be under.
   * @return CustomExpectedCondition<bool> true when element not 
   *         present in DOM.
   */
  public static function elementDestroyed(WebDriverBy $by, RemoteWebElement $parentElement = null) {
    return new CustomExpectedCondition(
      function ($driver) use ($element) {
            $els = $parentElement ? $parentElement->findElements($by) : $driver->findElements($by);
            return count($els)=== 0;
      }
    );
  }
  
  
}
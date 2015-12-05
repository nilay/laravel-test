<?php
namespace IntegrationTest\Lib;

class Util{
	static function hasString($string, $subString, $caseInsensitive = false){
	    return $caseInsensitive ? stripos($string, $subString) !== false : strpos($string, $subString) !== false;
	}
	
	static function startsWith($string, $subString){
		return substr($string, 0, strlen($subString)) === $subString;
	}

	static function endsWith($string, $subString){
		return substr($string, -1*strlen($subString)) === $subString;
	}

	
	static function diff($val1, $val2, $toleranceValue){
		return abs($val1 - $val2) < abs($toleranceValue);
	}
	
    static function arrayContainsArray($needle, $haystack){
        foreach ($needle as $key => $val) {
        	if(!array_key_exists($key, $haystack)){
        		return false;
        	}
        	if($val !== $haystack[$key]){
        		return false;
        	}
        }
        return true;
    } 
    
    
    /**
     * compare two string and return (in percentage) how much parts are similar 
     * "foo and bar" == "foo and bar"  (100%)
     * "foo and bar" == "foo and"		(66%)
     * "foo and bar" == "something else" (0%)
     */
    static function matchString($s1, $s2){
    	if(self::hasString($s1, $s2)){
    		return 100;
    	}
    	$s1Parts = explode(' ', $s1);
    	$s2Parts = explode(' ', $s2);
    	$matchPercent =0;
    	foreach($s2Parts as $part){
    		if(in_array(strtolower($part), array_map('strtolower', $s1Parts))){
    			$matchPercent += (100/count($s2Parts));
    		}
    	}
    	
    	return $matchPercent;
    }
      
}
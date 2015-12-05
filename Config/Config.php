<?php namespace IntegrationTest\Config;

require_once dirname(__FILE__) . "/default.php";

class Config{
	public static function get($constant){
		if(!defined($constant)){
			throw new \Exception("Undiefined config constant: $constant ");
		}
		return constant($constant);
	}
}

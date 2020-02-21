<?php namespace ProcessWire;

class CacheRedisConfig extends ModuleConfig {

	function __construct() {
		$this->add([[
			"name"			=>	"servername",
			"label"			=>	$this->_("Server"),
			"description"	=>	$this->_("Enter the server ip or hostname, or the path to a unix domain socket and check the 'Unix Socket' option."),
			"type"			=>	"text",
			"value"			=>	"127.0.0.1",
			"columnWidth"	=>	60
		], [
			"name"			=>	"serverport",
			"label"			=>	$this->_("Port"),
			"description"	=>	$this->_("Server TCP port"),
			"type"			=>	"text",
			"value"			=>	"6379",
			"columnWidth"	=>	40
		], [
			"name"			=>	"tls",
			"label"			=>	$this->_("Use TLS"),
			"description"	=>	$this->_("Use transport layer security"),
			"type"			=>	"checkbox"
		], [
			"name"			=>	"unix",
			"label"			=>	$this->_("Unix Socket"),
			"description"	=>	$this->_("Use a unix socket instead of a TCP connection"),
			"type"			=>	"checkbox"
		], [
			"name"			=>	"useAuth",
			"label"			=>	$this->_("Authenticate"),
			"description"	=>	$this->_("Check this box to enter authentication data for the Redis server"),
			"type"			=>	"checkbox"
		], [
			"name"			=>	"password",
			"label"			=>	$this->_("Password"),
			"description"	=>	$this->_("Password for login"),
			"type"			=>	"text",
			"value"			=>	"",
			"type"			=>	"password",
			"showIf"		=>	"useAuth=1"
		], [
			"name"			=>	"cacheactive",
			"label"			=>	$this->_("Active"),
			"description"	=>	$this->_("Caching is only active if this checkbox is checked, to avoid errors and delays if memache connection hasn't been configured yet."),
			"type"			=>	"checkbox",
			"value"			=>	""
		]]);
	}
	
}

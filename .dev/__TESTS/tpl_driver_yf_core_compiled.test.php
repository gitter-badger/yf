<?php

$GLOBALS['PROJECT_CONF']['tpl']['COMPILE_TEMPLATES'] = true;

require_once dirname(__FILE__).'/tpl_driver_yf_core.test.php';
class tpl_driver_yf_core_compiled_test extends tpl_driver_yf_core_test {
	public static function tearDownAfterClass() { _class('dir')->delete_dir('./stpls_compiled/', $delete_start_dir = true); }
#	function tearDown() { _class('dir')->delete_dir('./stpls_compiled/', $delete_start_dir = true); }
}

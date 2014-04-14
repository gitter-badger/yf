<?php

require_once dirname(__FILE__).'/tpl__setup.php';

class tpl_driver_yf_bugs_test extends tpl_abstract {
	public function test_bug_01() {
		$this->assertEquals('#description ', self::_tpl( '#description {execute(main,_show_block123123)}', array('description' => 'test') ));
	}
	public function test_bug_02() {
		$this->assertEquals(' {} ', self::_tpl( ' {} ', array('' => '') ));
	}
	public function test_bug_03() {
		$a = array('quantity' => 10, 'active' => 1);
		$this->assertEquals(' ok ', self::_tpl( '{if("quantity" gt 0)} ok {/if}', $a ));
		$this->assertEquals(' ok ', self::_tpl( '{if("active" ne 0)} ok {/if}', $a ));
		$this->assertEquals(' ok ', self::_tpl( '{if("quantity" gt "0" and "active" ne "0")} ok {/if}', $a ));
		$this->assertEquals(' ok ', self::_tpl( '{if(quantity gt 0 and active ne 0)} ok {/if}', $a ));
		$this->assertEquals(' ok ', self::_tpl( '{if("quantity" gt 0 and active ne 0)} ok {/if}', $a ));
		$this->assertEquals(' ok ', self::_tpl( '{if("quantity" gt 0 and "active" ne 0)} ok {/if}', $a ));

		$a = array('quantity' => 10, 'active' => 0);
		$this->assertEquals('', self::_tpl( '{if("quantity" gt 0 and "active" ne 0)} ok {/if}', $a ));
		$a = array('quantity' => 0, 'active' => 0);
		$this->assertEquals('', self::_tpl( '{if("quantity" gt 0 and "active" ne 0)} ok {/if}', $a ));
	}
	public function test_bug_04() {
		module_conf('main', 'unit_var2', '5');
		conf('unit_test_conf_item2', '6');
		$this->assertEquals(' ok ', self::_tpl( '{if(conf.unit_test_conf_item2 eq "6" and module_conf.main.unit_var2 eq "5")} ok {/if}' ));
		$this->assertEquals(' ok ', self::_tpl( '{if(conf.unit_test_conf_item2 eq 6 and module_conf.main.unit_var2 eq 5)} ok {/if}' ));
	}
	public function test_bug_05() {
		$this->assertEquals('.min', self::_tpl('{if(debug_mode eq 0)}.min{/if}', array('debug_mode' => 0)) );

		$tpl_str = '{catch(min_ext)}{if(debug_mode eq 0)}.min{/if}{/catch}{min_ext}';
		$this->assertEquals('.min', self::_tpl($tpl_str, array('debug_mode' => 0)) );
		$this->assertEquals('', self::_tpl($tpl_str, array('debug_mode' => 1)) );
	}
	public function test_bug_06() {
		$tpl_str = '
			{catch(min_ext)}{if(debug_mode eq 0)}.min{/if}{/catch}
			{if(css_framework eq "bs2" or css_framework eq "")}
				<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap{min_ext}.js"></script>
			{else}
				<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/3.1.0/js/bootstrap{min_ext}.js"></script>
			{/if}
		';
		$this->assertEquals('<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.js"></script>'
			, trim(self::_tpl($tpl_str, array('css_framework' => 'bs2', 'debug_mode' => 1))) );
		$this->assertEquals('<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/3.1.0/js/bootstrap.js"></script>'
			, trim(self::_tpl($tpl_str, array('css_framework' => 'bs3', 'debug_mode' => 1))) );
		$this->assertEquals('<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>'
			, trim(self::_tpl($tpl_str, array('css_framework' => 'bs2', 'debug_mode' => 0))) );
		$this->assertEquals('<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/3.1.0/js/bootstrap.min.js"></script>'
			, trim(self::_tpl($tpl_str, array('css_framework' => 'bs3', 'debug_mode' => 0))) );
	}
}
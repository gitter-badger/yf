<?php

require_once __DIR__.'/tpl__setup.php';

class tpl_driver_yf_bugs_test extends tpl_abstract {
	public static $_er = array();
	public static function setUpBeforeClass() {
		self::$_er = error_reporting();
		error_reporting(0);
	}
	public static function tearDownAfterClass() {
		error_reporting(self::$_er);
	}
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
	public function test_bug_07() {
		self::_tpl( 'Hello1', array(), 'unittest_include1' );
		self::_tpl( 'Hello2', array(), 'unittest_include2' );
		self::_tpl( 'Hello3', array(), 'unittest_include3' );
		$this->assertEquals('Hello1 Hello1 Hello1', self::_tpl( '{include("unittest_include1")} {include("unittest_include1")} {include("unittest_include1")}' ));
	}
	public function test_bug_08() {
		$this->assertEquals('', self::_tpl('{foreach(0)}</ul>{/foreach}') );
		$this->assertEquals('</ul>', self::_tpl('{foreach(1)}</ul>{/foreach}') );
		$this->assertEquals('</ul></ul>', self::_tpl('{foreach(2)}</ul>{/foreach}') );
		$this->assertEquals('</ul></ul></ul>', self::_tpl('{foreach(3)}</ul>{/foreach}') );
		$this->assertEquals('</ul></ul></ul>', self::_tpl('{foreach( 3 )}</ul>{/foreach}') );
		$this->assertEquals(str_repeat('</ul>', 100), self::_tpl('{foreach(100)}</ul>{/foreach}') );

		$this->assertEquals('', self::_tpl('{foreach(next_level_diff)}</ul>{/foreach}') );
		$this->assertEquals('', self::_tpl('{foreach(next_level_diff)}</ul>{/foreach}', array('next_level_diff' => 0)) );
		$this->assertEquals('</ul>', self::_tpl('{foreach(next_level_diff)}</ul>{/foreach}', array('next_level_diff' => 1)) );
		$this->assertEquals('</ul></ul>', self::_tpl('{foreach(next_level_diff)}</ul>{/foreach}', array('next_level_diff' => 2)) );
		$this->assertEquals('</ul></ul></ul>', self::_tpl('{foreach("next_level_diff")}</ul>{/foreach}', array('next_level_diff' => 3)) );
		$this->assertEquals('</ul></ul></ul>', self::_tpl('{foreach( "next_level_diff" )}</ul>{/foreach}', array('next_level_diff' => 3)) );
		$this->assertEquals('</ul></ul></ul>', self::_tpl('{foreach(next_level_diff)}</ul>{/foreach}', array('next_level_diff' => 3)) );
		$this->assertEquals(str_repeat('</ul>',100), self::_tpl('{foreach(next_level_diff)}</ul>{/foreach}', array('next_level_diff' => 100)) );
		$this->assertEquals('</ul></ul></ul>', self::_tpl('{foreach(data.next_level_diff)}</ul>{/foreach}', array('data' => array('next_level_diff' => 3))) );
	}
	public function test_bug_09() {
		$this->assertEquals('{foreach()}1{/foreach}', self::_tpl('{foreach()}1{/foreach}') );
		$this->assertEquals('', self::_tpl('{foreach(items)}1{/foreach}') );
		$this->assertEquals('', self::_tpl('{foreach(items)}1{/foreach}', array('items' => 0)) );
		$this->assertEquals('', self::_tpl('{foreach(items)}1{/foreach}', array('items' => array())) );
		$this->assertEquals('111', self::_tpl('{foreach(items)}1{/foreach}', array('items' => 3)) );
		$this->assertEquals('111', self::_tpl('{foreach(items)}1{/foreach}', array('items' => array(0,1,2))) );
		$this->assertEquals('111', self::_tpl('{foreach(items)}1{/foreach}', array('items' => array(1,2,3))) );
		$this->assertEquals('111', self::_tpl('{foreach(items)}1{/foreach}', array('items' => range(1,3))) );
		$this->assertEquals('111', self::_tpl('{foreach(items)}1{/foreach}', array('items' => array(array('k'=>'v'),array('k'=>'v'),array('k'=>'v')))) );

		$this->assertEquals('111,222', self::_tpl('{foreach(items)}1{/foreach},{foreach(items)}2{/foreach}', array('items' => range(1,3))) );
		$this->assertEquals('111,222,333', self::_tpl('{foreach(items)}1{/foreach},{foreach(items)}2{/foreach},{foreach(items)}3{/foreach}', array('items' => range(1,3))) );
		$this->assertEquals('1111,2222,3333', self::_tpl('{foreach(items)}1{/foreach},{foreach(items)}2{/foreach},{foreach(items)}3{/foreach}', array('items' => range(1,4))) );
		$this->assertEquals('1111,2222,1111', self::_tpl('{foreach(items)}1{/foreach},{foreach(items)}2{/foreach},{foreach(items)}1{/foreach}', array('items' => range(1,4))) );
	}
	public function test_bug_10() {
		$data = array(
			array('k1' => 'v1'),
			array('k1' => 'v2'),
			array('k2' => 'v22'),
			array('k3' => 'v33'),
		);
		$this->assertEquals(' 0=v1 _v1_  1=v2 _v2_  2=v22   3=v33  ', self::_tpl('{foreach(data)} {_key}={_val} {if(#.k1 ne "")}_{#.k1}_{/if} {/foreach}', array('data' => $data)) );
	}
	public function test_bug_11() {
		$this->assertEquals('{form}', self::_tpl('{form}', array()));
		$data = array( 'form' => form(array('name' => 'val'))->text('name') );
		$this->assertNotEquals('{form}', self::_tpl('{form}', $data));
		$this->assertGreaterThan( 100, strlen(self::_tpl('{form}', $data)) );

		$data = new stdClass();
		$data->key1 = 'val1';
		$data->form = form()->text('name');

		$this->assertEquals('{data.form}', self::_tpl('{data.form}', array()));
		$this->assertNotEquals('{data.form}', self::_tpl('{data.form}', array('data' => $data)));
		$this->assertGreaterThan( 100, strlen(self::_tpl('{data.form}', array('data' => $data))) );
	}
}
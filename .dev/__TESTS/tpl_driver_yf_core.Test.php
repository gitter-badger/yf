<?php

require_once dirname(__FILE__).'/tpl__setup.php';

class tpl_driver_yf_core_test extends tpl_abstract {
	public function test_10() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const("YF_PATH")}' ));
	}
	public function test_11() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const(\'YF_PATH\')}' ));
	}
	public function test_12_1() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const(YF_PATH)}' ));
	}
	public function test_12_2() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const( YF_PATH)}' ));
	}
	public function test_12_3() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const( YF_PATH )}' ));
	}
	public function test_12_4() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const( YF_PATH )}' ));
	}
	public function test_12_5() {
		$this->assertEquals(YF_PATH, self::_tpl( '{const(      YF_PATH         )}' ));
	}
	public function test_13_1() {
		$this->assertEquals('{const(WRONG-CONST)}', self::_tpl( '{const(WRONG-CONST)}' ));
	}
	public function test_13_2() {
		$this->assertEquals('{const( WRONG-CONST)}', self::_tpl( '{const( WRONG-CONST)}' ));
	}
	public function test_13_3() {
		$this->assertEquals('{const( WRONG-CONST )}', self::_tpl( '{const( WRONG-CONST )}' ));
	}
	public function test_14_1() {
		$this->assertEquals('{const()}', self::_tpl( '{const()}' ));
	}
	public function test_14_2() {
		$this->assertEquals('{const( )}', self::_tpl( '{const( )}' ));
	}
	public function test_15_1() {
		$this->assertEquals(substr(YF_PATH, 0, 8), self::_tpl( '{eval_code(substr(YF_PATH, 0, 8))}' ));
	}
	public function test_15_2() {
		$this->assertEquals(substr(YF_PATH, 0, 8), self::_tpl( '{eval_code( substr(YF_PATH, 0, 8))}' ));
	}
	public function test_15_3() {
		$this->assertEquals(substr(YF_PATH, 0, 8), self::_tpl( '{eval_code(substr(YF_PATH, 0, 8) )}' ));
	}
	public function test_15_4() {
		$this->assertEquals(substr(YF_PATH, 0, 8), self::_tpl( '{eval_code( substr(YF_PATH, 0, 8) )}' ));
	}
	public function test_20() {
		$this->assertEquals('val1', self::_tpl( '{replace1}', array('replace1' => 'val1') ));
	}
	public function test_21() {
		$this->assertEquals('val1', self::_tpl( '{replace-1}', array('replace-1' => 'val1') ));
	}
	public function test_22_1() {
		$this->assertEquals('{ replace-1}', self::_tpl( '{ replace-1}', array('replace-1' => 'val1') ));
	}
	public function test_22_2() {
		$this->assertEquals('{replace-1 }', self::_tpl( '{replace-1 }', array('replace-1' => 'val1') ));
	}
	public function test_22_3() {
		$this->assertEquals('{ replace-1 }', self::_tpl( '{ replace-1 }', array('replace-1' => 'val1') ));
	}
	public function test_23() {
		$this->assertEquals('<a href="http://google.com/">Google</a>', self::_tpl( '<a href="{url1}">Google</a>', array('url1' => 'http://google.com/') ));
	}
	public function test_24() {
		$this->assertEquals('http://google.com/http://google.com/http://google.com/', self::_tpl( '{url1}{url1}{url1}', array('url1' => 'http://google.com/') ));
	}
	public function test_25() {
		$this->assertEquals('<a href="http://yahoo.com/">Google</a>', self::_tpl( '{catch("url1")}http://yahoo.com/{/catch}<a href="{url1}">Google</a>', array('url1' => 'http://google.com/') ));
	}
	public function test_26() {
		$this->assertEquals('<a href="http://google.com/">Google</a>', self::_tpl( '{catch("url1")}http://yahoo.com/{/catch}{catch("url1")}http://google.com/{/catch}<a href="{url1}">Google</a>', array('url1' => 'http://google.com/') ));
	}
	public function test_27_1() {
		$this->assertEquals('<script>function myjs(){ var i = 0 }<script>', self::_tpl( '<script>function myjs(){ {js-var} }<script>', array('js-var' => 'var i = 0') ));
	}
	public function test_27_2() {
		$this->assertEquals('<script>function myjs(){ var i = 0; if(var > 0) alert("foreach"); }<script>', self::_tpl( '<script>function myjs(){ {js-var}; if(var > 0) alert("foreach"); }<script>', array('js-var' => 'var i = 0') ));
	}
	public function test_27_3() {
		$this->assertEquals('<script>function myjs(){ var i = 0; { js-var}; }<script>', self::_tpl( '<script>function myjs(){ {js-var}; { js-var}; }<script>', array('js-var' => 'var i = 0') ));
	}
	public function test_27_4() {
		$this->assertEquals('myjs(){ var i = 0; { js-var}; }', self::_tpl( 'myjs(){ {js-var}; { js-var}; }', array('js-var' => 'var i = 0') ));
	}
	public function test_27_5() {
		$this->assertEquals('myjs(){ var i = 0; {js-var }; }', self::_tpl( 'myjs(){ {js-var}; {js-var }; }', array('js-var' => 'var i = 0') ));
	}
	public function test_27_6() {
		$this->assertEquals('myjs(){ var i = 0; { js-var }; }', self::_tpl( 'myjs(){ {js-var}; { js-var }; }', array('js-var' => 'var i = 0') ));
	}
	public function test_28_1() {
		$this->assertEquals('{get.test}', self::_tpl( '{get.test}' ));
	}
	public function test_28_2() {
		$this->assertEquals('{ get.test }', self::_tpl( '{ get.test }' ));
	}
	public function test_29_1() {
		$this->assertEquals('true', self::_tpl( '{execute(test,true_for_unittest)}' ));
	}
	public function test_29_2() {
		$this->assertEquals('{ execute(test,true_for_unittest)}', self::_tpl( '{ execute(test,true_for_unittest)}' ));
	}
	public function test_29_3() {
		$this->assertEquals('{execute(test,true_for_unittest) }', self::_tpl( '{execute(test,true_for_unittest) }' ));
	}
	public function test_29_4() {
		$this->assertEquals('{ execute(test,true_for_unittest) }', self::_tpl( '{ execute(test,true_for_unittest) }' ));
	}
	public function test_29_5() {
		$this->assertEquals('true', self::_tpl( '{execute( test,true_for_unittest)}' ));
	}
	public function test_29_6() {
		$this->assertEquals('true', self::_tpl( '{execute(test,true_for_unittest )}' ));
	}
	public function test_29_7() {
		$this->assertEquals('true', self::_tpl( '{execute( test,true_for_unittest )}' ));
	}
	public function test_29_8() {
		$this->assertEquals('true', self::_tpl( '{execute(test ,true_for_unittest)}' ));
	}
	public function test_29_9() {
		$this->assertEquals('true', self::_tpl( '{execute(test, true_for_unittest)}' ));
	}
	public function test_29_10() {
		$this->assertEquals('true', self::_tpl( '{execute(test , true_for_unittest)}' ));
	}
	public function test_29_11() {
		$this->assertEquals('true', self::_tpl( '{execute( test , true_for_unittest )}' ));
	}
	public function test_29_12() {
		$this->assertEquals('true', self::_tpl( '{execute(test;true_for_unittest)}' ));
	}
	public function test_29_13() {
		$this->assertEquals('true', self::_tpl( '{execute( test;true_for_unittest )}' ));
	}
	public function test_29_14() {
		$this->assertEquals('true', self::_tpl( '{execute( test ; true_for_unittest )}' ));
	}
	public function test_29_15() {
		$this->assertEquals('true', self::_tpl( '{execute(test;true_for_unittest;param1=val1)}' ));
	}
	public function test_29_16() {
		$this->assertEquals('true', self::_tpl( '{execute(test,true_for_unittest,param1=val1)}' ));
	}
	public function test_29_17() {
		$this->assertEquals('true', self::_tpl( '{execute( test , true_for_unittest , param1=val1 )}' ));
	}
	public function test_29_18() {
		$this->assertEquals('true', self::_tpl( '{execute(test,true_for_unittest,param1=val1;param2=val2)}' ));
	}
	public function test_29_19() {
		$this->assertEquals('true', self::_tpl( '{execute(test,true_for_unittest,param1=val1;param2=val2;param3=val3)}' ));
	}
	public function test_29_20() {
		$this->assertEquals('true', self::_tpl( '{execute( test , true_for_unittest , param1=val1 ; param2=val2 )}' ));
	}
	public function test_29_21() {
		$this->assertNotEquals('tru', self::_tpl( '{execute(test,true_for_unittest)}' ));
	}
	public function test_29_22() {
		$this->assertEquals('  __true__', self::_tpl( '{catch("mytest1")}{execute(test,true_for_unittest)}{/catch}  __{mytest1}__' ));
	}
	public function test_29_30() {
		$this->assertEquals('22true33', self::_tpl( '{catch("mytest1")}22{execute(test,true_for_unittest)}33{/catch}{mytest1}' ));
	}
	public function test_29_31() {
		$this->assertEquals('22true33', self::_tpl( '{catch( "mytest1" )}22{execute(test,true_for_unittest)}33{/catch}{mytest1}' ));
	}
	public function test_29_32() {
		$this->assertEquals('22true33', self::_tpl( '{catch( mytest1 )}22{execute(test,true_for_unittest)}33{/catch}{mytest1}' ));
	}
	public function test_30() {
		$this->assertEquals('<script>function myjs(){ var i = 0 }<script>', self::_tpl( '{cleanup()}<script>function myjs(){ {js-var} }<script>{/cleanup}', array('js-var' => 'var i = 0') ));
	}
	public function test_31() {
		$this->assertEquals('val1,val2,val3', self::_tpl( '{sub.key1},{sub.key2},{sub.key3}', array('sub' => array('key1' => 'val1', 'key2' => 'val2', 'key3' => 'val3')) ));
	}
	public function test_50() {
		$this->assertEquals('', self::_tpl( '{{--STPL COMMENT--}}' ));
	}
	public function test_51() {
		$this->assertEquals('<!---->', self::_tpl( '<!--{{--STPL COMMENT--}}-->' ));
	}
	public function test_52() {
		$this->assertEquals('TEXT', self::_tpl( '{{--<!----}}TEXT{{---->--}}' ));
	}
	public function test_60() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "val1")}GOOD{/if}', array('key1' => 'val1') ));
	}
	public function test_61() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ne "")}GOOD{/if}', array('key1' => 'val1') ));
	}
	public function test_62() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" gt "1")}GOOD{/if}', array('key1' => '2') ));
	}
	public function test_63() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" lt "2")}GOOD{/if}', array('key1' => '1') ));
	}
	public function test_64() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ge "2")}GOOD{/if}', array('key1' => '2') ));
	}
	public function test_65() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ge "2")}GOOD{/if}', array('key1' => '3') ));
	}
	public function test_66() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" le "1")}GOOD{/if}', array('key1' => '1') ));
	}
	public function test_67() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" le "1")}GOOD{/if}', array('key1' => '0') ));
	}
	public function test_68() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ne "")}GOOD{else}BAD{/if}', array('key1' => 'not empty') ));
	}
	public function test_69() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "")}BAD{else}GOOD{/if}', array('key1' => 'not empty') ));
	}
	public function test_70() {
		$this->assertEquals(' GOOD ', self::_tpl( '{if("key1" eq "")} GOOD {else} BAD {/if}', array('key1' => '') ));
	}
	public function test_71() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ne "" and "key2" ne "")}GOOD{else}BAD{/if}', array('key1' => '1', 'key2' => '2') ));
	}
	public function test_72() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ne "" and "key2" ne "")}BAD{else}GOOD{/if}', array('key1' => '', 'key2' => '2') ));
	}
	public function test_73() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" ne "" and "key2" ne "")}BAD{else}GOOD{/if}', array('key1' => '', 'key2' => '') ));
	}
	public function test_74() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "" and "key2" eq "")}GOOD{else}BAD{/if}', array('key1' => '', 'key2' => '') ));
	}
	public function test_75() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "")}{if("key2" eq "")}GOOD{/if}{/if}', array('key1' => '', 'key2' => '') ));
	}
	public function test_76() {
		$this->assertEquals('', self::_tpl( '{if("key1" eq "")}{if("key2" eq "")}GOOD{/if}{/if}', array('key1' => '1', 'key2' => '2') ));
	}
	public function test_77() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "1")}{if("key2" eq "2")}{if("key3" eq "3")}GOOD{/if}{/if}{/if}', array('key1' => '1', 'key2' => '2', 'key3' => '3') ));
	}
	public function test_78() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "1" and "key2" eq "2" and "key3" eq "3")}GOOD{/if}', array('key1' => '1', 'key2' => '2', 'key3' => '3') ));
	}
	public function test_79() {
		$this->assertEquals('GOOD', self::_tpl( '{if("key1" eq "1" and "key2" eq "2" and "key3" eq "3" and "key4" eq "4" and "key5" eq "5")}GOOD{/if}', array('key1' => '1', 'key2' => '2', 'key3' => '3', 'key4' => '4', 'key5' => '5') ));
	}
	public function test_80() {
		$this->assertEquals('1111111111', self::_tpl( '{foreach(10)}1{/foreach}' ));
	}
	public function test_81() {
		$this->assertEquals(' 1  2  3  4 ', self::_tpl( '{foreach("testarray")} {_val} {/foreach}', array('testarray' => array(1,2,3,4)) ));
	}
	public function test_82() {
		$this->assertEquals(' 0  1  2  3 ', self::_tpl( '{foreach("testarray")} {_key} {/foreach}', array('testarray' => array(1,2,3,4)) ));
	}
	public function test_83() {
		$this->assertEquals(' 4  4  4  4 ', self::_tpl( '{foreach("testarray")} {_total} {/foreach}', array('testarray' => array(1,2,3,4)) ));
	}
	public function test_84() {
		$this->assertEquals(' 1  2  3 ', self::_tpl( '{foreach("testarray")} {_num} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_85() {
		$this->assertEquals(' 42  0  0 ', self::_tpl( '{foreach("testarray")} {if("_first" eq "1")}42{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_86() {
		$this->assertEquals(' 0  0  42 ', self::_tpl( '{foreach("testarray")} {if("_last" eq "1")}42{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_87() {
		$this->assertEquals(' 42  0  42 ', self::_tpl( '{foreach("testarray")} {if("_even" eq "1")}42{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_88() {
		$this->assertEquals(' 0  42  0 ', self::_tpl( '{foreach("testarray")} {if("_odd" eq "1")}42{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_89() {
		$this->assertEquals(' 0  42  0 ', self::_tpl( '{foreach("testarray")} {if("_key" eq "1")}42{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_90() {
		$this->assertEquals(' 42  0  0 ', self::_tpl( '{foreach("testarray")} {if("_val" eq "5")}42{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_91() {
		$this->assertEquals(' 0  0  7  0  0 ', self::_tpl( '{foreach("testarray")} {if("_num" eq "3")}{_val}{else}0{/if} {/foreach}', array('testarray' => array(5,6,7,8,9)) ));
	}
	public function test_92() {
		$this->assertEquals(' 1  1  1 ', self::_tpl( '{foreach("testarray")} {if("_total" eq "3")}1{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_93() {
		$this->assertEquals(' 1  1  1 ', self::_tpl( '{foreach( "testarray" )} {if("_total" eq "3")}1{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_94() {
		$this->assertEquals(' 1  1  1 ', self::_tpl( '{foreach(\'testarray\')} {if("_total" eq "3")}1{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_95() {
		$this->assertEquals(' 1  1  1 ', self::_tpl( '{foreach( \'testarray\' )} {if("_total" eq "3")}1{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_96() {
		$this->assertEquals(' 1  1  1 ', self::_tpl( '{foreach(testarray)} {if("_total" eq "3")}1{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_97() {
		$this->assertEquals(' 1  1  1 ', self::_tpl( '{foreach( testarray )} {if("_total" eq "3")}1{else}0{/if} {/foreach}', array('testarray' => array(5,6,7)) ));
	}
	public function test_100() {
		$this->assertEquals(
"1). <small>(key: One)</small>
<b style='color:red;'>First!!!</b><br />
<span style='color: blue;'>name: First<br />, num_items: 4<br /></span>, <br />
2). <small>(key: Two)</small>
<span style='color: green;'>name: Second<br />, num_items: 4<br /></span>, <br />
3). <small>(key: Three)</small>
<span style='color: blue;'>name: Third<br />, num_items: 4<br /></span>, <br />
4). <small>(key: Four)</small>
<span style='color: green;'>name: Fourth<br />, num_items: 4<br /></span>"
		, self::_tpl( 
"{foreach('test_array_2')}
{_num}). <small>(key: {_key})</small>
{if('_first' eq 1)}<b style='color:red;'>First!!!</b><br />\n{/if}
<span style='{if('_even' eq 1)}color: blue;{/if}{if('_odd' eq 1)}color: green;{/if}'>name: {#.name}<br />, num_items: {_total}<br /></span>{if('_last' ne 1)}, <br />\n{/if}
{/foreach}"
		, array(
			'test_array_1'  => array('One', 'Two', 'Three', 'Four'),
			'test_array_2'  => array(
				'One'   => array('name'  => 'First'),
				'Two'   => array('name'  => 'Second'),
				'Three' => array('name'  => 'Third'),
				'Four'  => array('name'  => 'Fourth'),
			),
			'cond_1' => 1,
			'cond_2' => 2,
			'cond_3' => 2,
		) ));
	}
	public function test_101() {
		$this->assertEquals(' name1:21  name2:22  name3:23 ', self::_tpl( '{foreach("testarray")} {#.name}:{#.age} {/foreach}', array(
			'testarray' => array(
				5 => array('name' => 'name1', 'age' => 21),
				6 => array('name' => 'name2', 'age' => 22),
				7 => array('name' => 'name3', 'age' => 23),
			),
		) ));
	}
	public function test_120() {
		$this->assertEquals('GOOD', self::_tpl( '{if("sub.key1" eq "val1")}GOOD{else}BAD{/if}', array('sub' => array('key1' => 'val1')) ));
	}
	public function test_121() {
		$this->assertEquals('GOOD', self::_tpl( '{if("sub.key1" eq "val1" and "sub.key2" ne "gggg")}GOOD{else}BAD{/if}', array('sub' => array('key1' => 'val1', 'key2' => 'val2')) ));
	}
	public function test_122() {
		define('MY_TEST_CONST_1', '42');
		$this->assertEquals('GOOD', self::_tpl( '{if("sub.key1" eq "val1" and "const.MY_TEST_CONST_1" eq "42")}GOOD{else}BAD{/if}', array('sub' => array('key1' => 'val1')) ));
	}
	public function test_123() {
		$this->assertEquals('GOOD', self::_tpl( '{if("%string" eq "string")}GOOD{else}BAD{/if}' ));
	}
	public function test_124() {
		$this->assertEquals('GOOD', self::_tpl( '{if( "sub.key1" eq "val1" )}GOOD{else}BAD{/if}', array('sub' => array('key1' => 'val1')) ));
	}
	public function test_125() {
		$this->assertEquals('GOOD', self::_tpl( '{if(sub.key1 eq val1)}GOOD{else}BAD{/if}', array('sub' => array('key1' => 'val1')) ));
	}
	public function test_126() {
		$this->assertEquals('GOOD', self::_tpl( '{if( sub.key1 eq val1 )}GOOD{else}BAD{/if}', array('sub' => array('key1' => 'val1')) ));
	}
	public function test_131() {
		$this->assertEquals('val1 val2', self::_tpl( '{sub.key1} {sub.key2}', array('sub' => array('key1' => 'val1', 'key2' => 'val2')) ));
	}
	public function test_140() {
		$this->assertEquals('val1', self::_tpl( '{key1|trim}', array('key1' => ' val1 ') ));
	}
	public function test_141() {
		$this->assertEquals('val1', self::_tpl( '{key1|trim|urlencode}', array('key1' => ' val1 ') ));
	}
	public function test_142() {
		$this->assertEquals('val1 val2', self::_tpl( '{sub.key1|trim} {sub.key2|urlencode}', array('sub' => array('key1' => ' val1 ', 'key2' => 'val2')) ));
	}
	public function test_143() {
		$a = array( array('key1' => ' val11 '), array('key1' => ' val21 '), );
		$this->assertEquals('+val11++val21+', self::_tpl( '{foreach("testarray")}+{#.key1|trim}+{/foreach}', array('testarray' => $a) ));
	}
}
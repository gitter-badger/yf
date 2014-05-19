<?php

require dirname(__FILE__).'/yf_unit_tests_setup.php';
require dirname(__FILE__).'/db_setup.php';

class class_db_query_builder_test extends PHPUnit_Framework_TestCase {
	public static $_er = array();
	public static function setUpBeforeClass() {
		self::$_er = error_reporting();
		error_reporting(0);
	}
	public static function tearDownAfterClass() {
		error_reporting(self::$_er);
	}
	private function qb() {
		return _class('db')->query_builder();
	}
	public function test_select1() {
		$this->assertEquals( 'SELECT *', self::qb()->select()->_sql['select'][0] );
		$this->assertEquals( 'SELECT *', self::qb()->select('*')->_sql['select'][0] );
		$this->assertEquals( 'SELECT *', self::qb()->select(' *')->_sql['select'][0] );
		$this->assertEquals( 'SELECT *', self::qb()->select('* ')->_sql['select'][0] );
		$this->assertEquals( 'SELECT *', self::qb()->select('   *   ')->_sql['select'][0] );
		$this->assertFalse( self::qb()->select()->sql() );
		$this->assertFalse( self::qb()->select()->__toString() );
	}
	public function test_select2() {
		$this->assertEquals( 'SELECT `id`', self::qb()->select('id')->_sql['select'][0] );
		$this->assertFalse( self::qb()->select('id')->sql() );
	}
	public function test_select3() {
		$this->assertEquals( 'SELECT `id`, `name`', self::qb()->select('id','name')->_sql['select'][0] );
		$this->assertFalse( self::qb()->select('id','name')->sql() );
	}
	public function test_select4() {
		$this->assertNull( self::qb()->select('')->_sql['select'][0] );
#		$this->assertNull( self::qb()->select(array())->_sql['select'][0] );
		$this->assertNull( self::qb()->select(false)->_sql['select'][0] );
		$this->assertNull( self::qb()->select(0)->_sql['select'][0] );
		$this->assertNull( self::qb()->select('0')->_sql['select'][0] );

		$this->assertFalse( self::qb()->select('')->sql() );
		$this->assertFalse( self::qb()->select(array())->sql() );
		$this->assertFalse( self::qb()->select(false)->sql() );
		$this->assertFalse( self::qb()->select(0)->sql() );
		$this->assertFalse( self::qb()->select('0')->sql() );
	}
	public function test_select5() {
		$this->assertEquals( 'SELECT u.id, s.id, t.pid', self::qb()->select('u.id', 's.id', 't.pid')->_sql['select'][0] );
		$this->assertEquals( 'SELECT u.id AS `user_id`', self::qb()->select(array('u.id' => 'user_id'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT u.id AS `user_id`, a.id AS `article_id`, b.id AS `blog_id`', self::qb()->select(array('u.id' => 'user_id', 'a.id' => 'article_id', 'b.id' => 'blog_id'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT u.id AS `user_id`, a.id AS `article_id`, b.id AS `blog_id`', self::qb()->select(array('u.id' => 'user_id'), array('a.id' => 'article_id'), array('b.id' => 'blog_id'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT COUNT(*) AS `num`', self::qb()->select(array('COUNT(*)' => 'num'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT COUNT(id) AS `num`', self::qb()->select(array('COUNT(id)' => 'num'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT COUNT(u.id) AS `num`', self::qb()->select(array('COUNT(u.id)' => 'num'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT DISTINCT u.id', self::qb()->select('DISTINCT u.id')->_sql['select'][0] );
		$this->assertEquals( 'SELECT DISTINCT u.id AS `num`', self::qb()->select(array('DISTINCT u.id' => 'num'))->_sql['select'][0] );
		$this->assertEquals( 'SELECT DISTINCT u.id AS `num`, a.id AS `article_id`', self::qb()->select( function(){return 'DISTINCT u.id AS `num`';}, function(){return 'a.id AS `article_id`';} )->_sql['select'][0] );
#		$this->assertEquals( 'SELECT u.id, a.id, b.id', self::qb()->select(array('u.id', 'a.id', 'b.id'))->_sql['select'][0] );
#		$this->assertEquals( 'SELECT `id id`', self::qb()->select('id id')->_sql['select'][0] );
	}
	public function test_select_string_as() {
		$this->assertEquals( 'SELECT s.id AS `sid`', self::qb()->select('s.id as sid')->_sql['select'][0] );
		$this->assertEquals( 'SELECT s.id AS `sid`, u.id AS `uid`', self::qb()->select('s.id as sid', 'u.id as uid')->_sql['select'][0] );
		$this->assertEquals( 'SELECT u.id AS `uid`', self::qb()->select(array('u.id as uid'))->_sql['select'][0] );
	}
	public function test_from() {
		$this->assertFalse( self::qb()->from()->sql() );
		$this->assertFalse( self::qb()->select()->from()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user`', self::qb()->from('user')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user`', self::qb()->select()->from('user')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user`, `'.DB_PREFIX.'articles`', self::qb()->select()->from('user','articles')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u`', self::qb()->select()->from(array('user' => 'u'))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u`, `'.DB_PREFIX.'articles` AS `a`', self::qb()->select()->from(array('user' => 'u', 'articles' => 'a'))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u`, `'.DB_PREFIX.'articles` AS `a`', self::qb()->select()->from(array('user' => 'u'), array('articles' => 'a'))->sql() );
	}
	public function test_from_string_as() {
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u`', self::qb()->select()->from('user as u')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u`, `'.DB_PREFIX.'articles` AS `a`', self::qb()->select()->from('user as u', 'articles as a')->sql() );
	}
	public function test_where() {
		$this->assertFalse( self::qb()->where()->sql() );
		$this->assertFalse( self::qb()->from()->where()->sql() );
		$this->assertFalse( self::qb()->select()->from()->where()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\'', self::qb()->from('user')->where('id','=','1')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\'', self::qb()->from('user')->where(array('id','=',1))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user`, `'.DB_PREFIX.'articles` WHERE u.id=\'1\'', self::qb()->from('user','articles')->where(array('u.id','=',1))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\'', self::qb()->from(array('user' => 'u'))->where(array('u.id','=',1))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' AND u.gid=\'4\'', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=','1'),'and',array('u.gid','=','4'))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' OR u.gid=\'4\'', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=','1'),'or',array('u.gid','=','4'))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' XOR u.gid=\'4\'', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=','1'),'xor',array('u.gid','=','4'))->sql() );
	}
	public function test_where_simple_syntax() {
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\'', self::qb()->from('user as u')->where('u.id = 1')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id>\'1\'', self::qb()->from('user as u')->where('u.id > 1')->sql() );
	}
	public function test_where_or() {
#		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' OR u.gid=\'4\'', self::qb()->from('user as u')->where('u.id = 1')->where_or('u.gid = 4')->sql() );
	}
	public function test_whereid() {
		$this->assertFalse( self::qb()->whereid()->sql() );
		$this->assertFalse( self::qb()->from()->whereid()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\'', self::qb()->from('user')->whereid(1)->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\'', self::qb()->from('user')->whereid(1, '')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `uid`=\'1\'', self::qb()->from('user')->whereid(1, 'uid')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE u.id=\'1\'', self::qb()->from('user')->whereid(1, 'u.id')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id` IN(1,2,3)', self::qb()->from('user')->whereid(array(1,2,3))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `uid` IN(1,2,3)', self::qb()->from('user')->whereid(array(1,2,3), 'uid')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE u.id IN(1,2,3)', self::qb()->from('user')->whereid(array(1,2,3), 'u.id')->sql() );
	}
	public function test_join() {
		$this->assertFalse( self::qb()->join()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` JOIN `'.DB_PREFIX.'articles` AS `a` ON `u`.`id`=`a`.`id`',
			self::qb()->select()->from('user as u')->join(array('articles' => 'a'), array('u.id' => 'a.id'))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` JOIN `'.DB_PREFIX.'articles` AS `a` ON u.id=a.id', 
			self::qb()->select()->from('user as u')->join('articles as a', 'u.id = a.id')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` LEFT JOIN `'.DB_PREFIX.'articles` AS `a` ON u.id=a.id', 
			self::qb()->select()->from('user as u')->left_join('articles as a', 'u.id = a.id')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` RIGHT JOIN `'.DB_PREFIX.'articles` AS `a` ON u.id=a.id', 
			self::qb()->select()->from('user as u')->right_join('articles as a', 'u.id = a.id')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` INNER JOIN `'.DB_PREFIX.'articles` AS `a` ON u.id=a.id', 
			self::qb()->select()->from('user as u')->inner_join('articles as a', 'u.id = a.id')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` INNER JOIN `'.DB_PREFIX.'articles` AS `a` ON u.id=a.id', 
			self::qb()->select()->from('user as u')->join('articles as a', 'u.id = a.id', 'inner')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` INNER JOIN `'.DB_PREFIX.'articles` AS `a` ON u.id=a.id  INNER JOIN `'.DB_PREFIX.'blogs` AS `b` ON u.id=b.id', 
			self::qb()->select()->from('user as u')->inner_join('articles as a', 'u.id = a.id')->inner_join('blogs as b', 'u.id = b.id')->sql() );
	}
	public function test_group_by() {
		$this->assertFalse( self::qb()->group_by()->sql() );
		$this->assertFalse( self::qb()->from()->where()->group_by()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\' GROUP BY `gid`', self::qb()->from('user')->where(array('id','=',1))->group_by('gid')->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE `id`=\'1\' GROUP BY u.id', self::qb()->from('user as u')->whereid(1)->group_by('u.id')->sql() );
	}
	public function test_having() {
		$this->assertFalse( self::qb()->having()->sql() );
		$this->assertFalse( self::qb()->from()->where()->group_by()->having()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\' GROUP BY `gid` HAVING `gid`=\'4\'', 
			self::qb()->from('user')->where(array('id','=',1))->group_by('gid')->having(array('gid','=',4))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' GROUP BY u.gid HAVING u.gid=\'4\'', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=',1))->group_by('u.gid')->having(array('u.gid','=',4))->sql() );
	}
	public function test_order_by() {
		$this->assertFalse( self::qb()->order_by()->sql() );
		$this->assertFalse( self::qb()->from()->where()->having()->group_by()->order_by()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\' GROUP BY `gid` HAVING `gid`=\'4\' ORDER BY `id` DESC', 
			self::qb()->from('user')->where(array('id','=',1))->group_by('gid')->having(array('gid','=',4))->order_by(array('id' => 'desc'))->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' GROUP BY u.gid HAVING u.gid=\'4\' ORDER BY u.id ASC', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=',1))->group_by('u.gid')->having(array('u.gid','=',4))->order_by('u.id')->sql() );
	}
	public function test_limit() {
		$this->assertFalse( self::qb()->limit()->sql() );
		$this->assertFalse( self::qb()->limit()->sql() );
		$this->assertFalse( self::qb()->from()->limit()->sql() );
		$this->assertFalse( self::qb()->from()->where()->having()->group_by()->order_by()->limit()->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` WHERE `id`=\'1\' GROUP BY `gid` HAVING `gid`=\'4\' ORDER BY `id` DESC LIMIT 10', 
			self::qb()->from('user')->where(array('id','=',1))->group_by('gid')->having(array('gid','=',4))->order_by(array('id' => 'desc'))->limit(10)->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' GROUP BY u.gid HAVING u.gid=\'4\' ORDER BY u.id ASC LIMIT 20, 5', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=',1))->group_by('u.gid')->having(array('u.gid','=',4))->order_by('u.id')->limit(5, 20)->sql() );
	}
	// Testign that changing order of method calls not changing result SQL
	public function test_complex() {
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' GROUP BY u.gid HAVING u.gid=\'4\' ORDER BY u.id ASC LIMIT 20, 5', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=',1))->group_by('u.gid')->having(array('u.gid','=',4))->order_by('u.id')->limit(5, 20)->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' GROUP BY u.gid HAVING u.gid=\'4\' ORDER BY u.id ASC LIMIT 20, 5', 
			self::qb()->from(array('user' => 'u'))->where(array('u.id','=',1))->group_by('u.gid')->having(array('u.gid','=',4))->order_by('u.id')->limit(5, 20)->sql() );
		$this->assertEquals( 'SELECT * FROM `'.DB_PREFIX.'user` AS `u` WHERE u.id=\'1\' GROUP BY u.gid HAVING u.gid=\'4\' ORDER BY u.id ASC LIMIT 20, 5', 
			self::qb()->group_by('u.gid')->where(array('u.id','=',1))->order_by('u.id')->limit(5, 20)->from(array('user' => 'u'))->having(array('u.gid','=',4))->sql() );
	}
}
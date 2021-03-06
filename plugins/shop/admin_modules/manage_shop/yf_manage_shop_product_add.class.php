<?php

class yf_manage_shop_product_add {
	function product_add () {
		$supplier_id = (int)module('manage_shop')->SUPPLIER_ID;
		$r = array('back_link' => './?object='.main()->_get('object').'&action=products');
		return form2($r + (array)$_POST)
			->validate(array('name' => 'trim|required'))
			->db_insert_if_ok('shop_products', array('name'), array('add_date' => time(), 'active' => 0, 'supplier_id' => $supplier_id), array('on_after_update' => function($data, $table, $fields, $type, &$extra) {
				$id = db()->insert_id();
				//initial cleanup
				db()->query("DELETE FROM `".db('shop_aliases')."` WHERE `dst_item_id`='{$id}' AND `type`='products'");
				db()->query("DELETE FROM `".db('shop_product_images')."` WHERE `product_id`='{$id}'");
				db()->query("DELETE FROM `".db('shop_products_productparams')."` WHERE `product_id`='{$id}'");				
				db()->query("DELETE FROM `".db('shop_product_to_category')."` WHERE `product_id`='{$id}'"); 
				
				$extra['redirect_link'] = './?object='.main()->_get('object').'&action=product_edit&id='.$id;
				common()->admin_wall_add(array(t('shop product added: %name', array('%name' => $_POST['name'])), $id));
				module('manage_shop')->_product_cache_purge($id);
			}))
			->text('name')
			->save_and_back();
	}
}

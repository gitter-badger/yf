<?php

/**
* YF Cache driver abstract
*/
abstract class yf_cache_driver {
	abstract protected function get($name, $ttl = 0, $params = array());
	abstract protected function set($name, $data, $ttl = 0);
	abstract protected function del($name);
	abstract protected function multi_get(array $names, $ttl = 0, $params = array());
	abstract protected function multi_set(array $data, $ttl = 0);
	abstract protected function multi_del(array $names);
	abstract protected function clean($name);
}
<?php

/**
*/
class yf_db_utils_helper_index {

	protected $db = null;
	protected $utils = null;
	protected $db_name = '';
	protected $table = '';
	protected $index = '';

	/**
	* Catch missing method call
	*/
	function __call($name, $args) {
		return main()->extend_call($this, $name, $args);
	}

	/**
	* We cleanup object properties when cloning
	*/
	function __clone() {
		foreach ((array)get_object_vars($this) as $k => $v) {
			$this->$k = null;
		}
	}

	/**
	*/
	function _setup($params) {
		foreach ($params as $k => $v) {
			$this->$k = $v;
		}
		return $this;
	}

	/**
	*/
	function exists($extra = array(), &$error = false) {
		return $this->utils->index_exists($this->db_name.'.'.$this->table, $this->index, $extra, $error);
	}

	/**
	*/
	function info($extra = array(), &$error = false) {
		return $this->utils->index_info($this->db_name.'.'.$this->table, $this->index, $extra, $error);
	}

	/**
	*/
	function drop($extra = array(), &$error = false) {
		return $this->utils->drop_index($this->db_name.'.'.$this->table, $this->index, $extra, $error);
	}

	/**
	*/
	function add(array $data, $extra = array(), &$error = false) {
		return $this->utils->add_index($this->db_name.'.'.$this->table, $this->index, $data, $extra, $error);
	}

	/**
	*/
	function update(array $data, $extra = array(), &$error = false) {
		return $this->utils->update_index($this->db_name.'.'.$this->table, $this->index, $data, $extra, $error);
	}
}

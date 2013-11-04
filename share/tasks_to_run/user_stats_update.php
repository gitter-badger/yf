<?php

// THIS TASKS OPERATIONS:
// Update users stats
class task_item {

	var $class		= "";
	var $task		= "";

	
	// ADD YOUR CODE HERE
	function run_task()	{
		// Check for correct call
		if (!defined("INSIDE_TASK_MANAGER")) {
			return false;
		}
		_class("user_stats")->_refresh_all_stats();
		// Log to log table - modify but dont delete
		$this->class->append_task_log($this->task, 'User Stats Updated');
	}

	
	// DO NOT MODIFY!
	function register_class(&$class) {
		$this->class = $class;
	}
	
	
	// DO NOT MODIFY!
	function pass_task($this_task) {
		$this->task = $this_task;
	}
}

?>
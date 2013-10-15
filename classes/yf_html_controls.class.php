<?php

/**
* HTML controls high-level methods
*
* @package		YF
* @author		YFix Team <yfix.dev@gmail.com>
* @version		1.0
 */
class yf_html_controls {

	/** @var bool */
	public $AUTO_ASSIGN_IDS = true;

	/**
	*/
	function select_box ($name, $values = array(), $selected = '', $show_text = true, $type = 2, $add_str = '', $translate = 0, $level = 0) {
		// Passing params as array
		if (is_array($name)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			if (!$extra['no_translate']) {
				$values = t($values);
			}
			$selected = $extra['selected'];
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 0;
			$type = isset($extra['type']) ? $extra['type'] : 2;
			$translate = isset($extra['translate']) ? $extra['translate'] : 0;
			$level = isset($extra['level']) ? $extra['level'] : 0;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			$extra['class'] .= ' form-control';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		} else {
			$add_str .= ' class="form-control" ';
		}
		if (!$values) {
			return false;
		}
		$selected = strval($selected);
		// (example: $add_str = 'size=6')
		$body = $level == 0 ? PHP_EOL.'<select name="'.$name.'"'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_box"' : '').$add_str.">".PHP_EOL : '';
		if ($show_text && $level == 0) {
			$body .= '<option value="">'.($show_text == 1 ? '-'.t('select').' '.t($name).'-' : $show_text).'</option>'.PHP_EOL;
		}
		$self_func = __FUNCTION__;
		foreach ((array)$values as $key => $cur_value) {
			if (is_array($cur_value)) {
				$body .= '<optgroup label="'.$key.'" title="'.($translate ? t($key) : $key).'">'.PHP_EOL;
				$body .= $this->$self_func($name, $cur_value, $selected, $show_text, $type, $add_str, $translate, $level + 1);
				$body .= '</optgroup>'.PHP_EOL;
			} else {
				$_what_compare = strval($type == 1 ? $cur_value : $key);
				$body .= '<option value="'.$key.'" '.($_what_compare == $selected ? 'selected="selected"' : '').'>'.($translate ? t($cur_value) : $cur_value).'</option>'.PHP_EOL;
			}
		}
		$body .= $level == 0 ? '</select>'.PHP_EOL : '';
		return $body;
	}

	/**
	* Alias
	*/
	function multi_select_box($name, $values = array(), $selected = '', $show_text = false, $type = 2, $add_str = '', $translate = 0, $level = 0, $disabled = false) {
		return $this->multi_select($name, $values, $selected, $show_text, $type, $add_str, $translate, $level, $disabled);
	}

	/**
	*/
	function multi_select($name, $values = array(), $selected = '', $show_text = false, $type = 2, $add_str = '', $translate = 0, $level = 0, $disabled = false) {
		// Passing params as array
		if (is_array($name)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			if (!$extra['no_translate']) {
				$values = t($values);
			}
			$selected = $extra['selected'];
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 0;
			$type = isset($extra['type']) ? $extra['type'] : 2;
			$translate = isset($extra['translate']) ? $extra['translate'] : 0;
			$level = isset($extra['level']) ? $extra['level'] : 0;
			$disabled = isset($extra['disabled']) ? $extra['disabled'] : false;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			$extra['class'] .= ' form-control';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		} else {
			$add_str .= ' class="form-control" ';
		}
		if (!$values) {
			return false;
		}
		if (!is_array($selected)) {
			$selected = strval($selected);
		}
		if ($disabled  == 1) {
			$disabled = 'disabled';
		} else {
			$disabled = '';
		}
		// (example: $add_str = 'size=6') disabled
		$body = $level == 0 ? PHP_EOL.'<select '.$disabled.' multiple name="'.$name.'[]"'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_box"' : '').$add_str.'>'.PHP_EOL : '';
		if ($show_text && $level == 0) {
			$body .= '<option value="">-'.t('select').' '.t($name).'-</option>'.PHP_EOL;
		}
		$self_func = __FUNCTION__;
		foreach ((array)$values as $key => $value) {
			if (is_array($value)) {
				$body .= '<optgroup label="'.$key.'" title="'.($translate ? t($key) : $key).'">'.PHP_EOL;
				$body .= $this->$self_func($name, $value, $selected, $show_text, $type, $add_str, $translate, $level + 1);
				$body .= '</optgroup>'.PHP_EOL;
			} else {
				// Selected value could be an array
				if (is_array($selected)) {
					if ($type == 1) {
						$sel_text = in_array($value, $selected) ? 'selected="selected"' : '';
					} else {
						$sel_text = isset($selected[$key]) ? 'selected="selected"' : '';
					}
				} elseif (strlen($selected)) {
					$_what_compare = strval($type == 1 ? $value : $key);
					$sel_text = $_what_compare == $selected ? 'selected="selected"' : '';
				} else {
					$sel_text = '';
				}
				$body .= '<option value="'.$key.'" '.$sel_text.'>'.($translate ? t($value) : $value).'</option>'.PHP_EOL;
			}
		}
		$body .= $level == 0 ? '</select>'.PHP_EOL : '';
		return $body;
	}

	/**
	*/
	function radio_box ($name, $values = array(), $selected = '', $flow_vertical = false, $type = 2, $add_str = '', $translate = 0) {
		if (is_array($name)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			if (!$extra['no_translate']) {
				$values = t($values);
			}
			$selected = $extra['selected'];
			$type = isset($extra['type']) ? $extra['type'] : 2;
			$translate = isset($extra['translate']) ? $extra['translate'] : 0;
			$flow_vertical = isset($extra['flow_vertical']) ? $extra['flow_vertical'] : false;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		}
		if (!$values) {
			return false;
		}
		$selected = strval($selected);
		foreach ((array)$values as $value => $val_name) {
			if (common()->BOXES_USE_STPL) { 
				$_what_compare = strval($type == 1 ? $val_name : $value);
				$replace = array(
					'name'		=> $name,
					'value'		=> $value,
					'selected'	=> $_what_compare == $selected ? 'checked="true"' : '',
					'add_str'	=> $add_str,
					'label'		=> $translate ? t($val_name) : $val_name,
					'divider'	=> $flow_vertical ? '<br />' : '&nbsp;',
				);
				$body .= tpl()->parse('system/common/radio_box_item', $replace);
			} else {
				$body .= '<label class="radio"><input type="radio" name="'.$name.'" id="check_'.$name.'" value="'.$value.'" '.$add_str.' '
					.((strval($value) == $selected) ? 'checked' : '').'>'.t($val_name).'</label>'.PHP_EOL;
			}
		}
		return $body;
	}

	/**
	* Simple check box
	*/
	function check_box ($name = '', $value = '', $selected = '', $add_str = '', $extra = array()) {
		if (is_array($name)) {
			$extra = $name;
			$name = $extra['name'];
#			$value = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
		}
		if (!is_array($extra)) {
			$extra = array();
		}
		$name = $extra['name'] ? $extra['name'] : 'checkbox';
		$value = $extra['value'] ? $extra['value'] : (strlen($value) ? $value : '1');
		$selected = $extra['selected'] ? $extra['selected'] : $selected;
		$id = $extra['id'] ? $extra['id'] : $name;
		$desc = $extra['desc'] ? $extra['desc'] : ucfirst(str_replace('_', '', $name));
		$translate = $extra['translate'] ? $extra['translate'] : true;
		$add_str = $extra['add_str'] ? $extra['add_str'] : $add_str;
		if ($extra['class']) {
			$add_str .= ' class="'.$extra['class'].'" ';
		}
		if ($extra['style']) {
			$add_str .= ' style="'.$extra['style'].'" ';
		}

		return '<label class="checkbox">'
				.'<input type="checkbox" name="'.$name.'" id="'.$id.'" value="'.$value.'"'
					.($selected ? ' checked="checked"' : '')
					.($add_str ? ' '.$add_str : '')
				.'> &nbsp;' // Please do not remove this whitespace :)
				.($translate ? t($extra['desc']) : $extra['desc'])
			.'</label>';
	}

	/**
	* Processing many checkboxes at one time
	*/
	function multi_check_box ($name, $values = array(), $selected = array(), $flow_vertical = false, $type = 2, $add_str = '', $translate = 0, $name_as_array = false) {
		if (is_array($name)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			if (!$extra['no_translate']) {
				$values = t($values);
			}
			$selected = $extra['selected'];
			$type = isset($extra['type']) ? $extra['type'] : 2;
			$translate = isset($extra['translate']) ? $extra['translate'] : 0;
			$flow_vertical = isset($extra['flow_vertical']) ? $extra['flow_vertical'] : false;
			$name_as_array = isset($extra['name_as_array']) ? $extra['name_as_array'] : false;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		}
		if (!$values) {
			return false;
		}
		if (!is_array($selected)) {
			$selected = strval($selected);
		}
		foreach ((array)$values as $key => $value) {
			$sel_text = '';
			// Selected value could be an array
			if (is_array($selected)) {
				if ($type == 1) {
					$sel_text = in_array($value, $selected) ? 'checked' : '';
				} else {
					$sel_text = isset($selected[$key]) ? 'checked' : '';
				}
			} elseif (strlen($selected)) {
				$_what_compare = strval($type == 1 ? $value : $key);
				$sel_text = $_what_compare == $selected ? 'checked="true"' : '';
			} else {
				$sel_text = '';
			}
			
			if ($name_as_array) {
				$val_name = $name.'['.$key.']';
			} else {
				$val_name = $name.'_'.$key;
			}
			if (common()->BOXES_USE_STPL) {
				$replace = array(
					'name'		=> $val_name,
					'value'		=> $key,
					'selected'	=> $sel_text,
					'add_str'	=> $add_str,
					'label'		=> $translate ? t($value) : $value,
					'divider'	=> $flow_vertical ? '<br />' : '&nbsp;',
				);
				$body .= tpl()->parse('system/common/check_box_item', $replace);
			} else {
// TODO: auto ID
				$body .= '<input type="checkbox" name="'.$val_name.'" class="check" value="'.$key.'" '.$sel_text.' '.$add_str.'>'
					.($translate ? t($value) : $value)
					.($flow_vertical ? '<br />' : '&nbsp;'). PHP_EOL;
			}
		}
		return $body;
	}

	/**
	*/
	function date_box ($selected = '', $years = '', $name_postfix = '', $add_str = '', $show_what = 'ymd', $show_text = 1, $translate = 1) {
		if (is_array($selected)) {
			$extra = $name;
			$selected = $extra['selected'];

			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			$selected = $extra['selected'];

			$years = isset($extra['years']) ? $extra['years'] : '';
			$show_what = isset($extra['show_what']) ? $extra['show_what'] : 'ymd';
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 1;
			$translate = isset($extra['translate']) ? $extra['translate'] : 1;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		}
		if (strlen($selected))	{
			// Process timestamp (convert it to the 'Y-m-d' pattern)
			if (is_numeric($selected)) {
				$selected = gmdate('Y-m-d', $selected);
			}
			list($year, $month, $day) = explode('-', $selected);
		}
		if (strlen($years)) {
			list($start_year, $end_year) = explode('-', $years);
		} else {
			$start_year = 1900;
			$end_year = gmdate('Y');
		}
		$y .= '<select name="year'.$name_postfix.'"'.($this->AUTO_ASSIGN_IDS ? ' id="year_'.$name_postfix.'_box"' : '').'>'.PHP_EOL;
		$y .= $show_text ? '<option '.(!$year ? 'selected="selected"' : '').' value="">-'.($translate ? t('year') : 'year').'-</option>'.PHP_EOL : '';
		for ($a = $start_year; $a <= $end_year; $a++) {
			$y .= '<option '.(($year == $a) ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$y .= '</select>'.PHP_EOL;
		$m .= '<select name="month'.$name_postfix.'"'.($this->AUTO_ASSIGN_IDS ? ' id="month_'.$name_postfix.'_box"' : '').'>'.PHP_EOL;
		$m .= $show_text ? '<option '.(!$month ? 'selected="selected"' : '').' value="">-'.($translate ? t('month') : 'month').'-</option>'.PHP_EOL : '';
		for ($a = 1; $a <= 12; $a++) {
			$m .= '<option '.(($month == $a) ? 'selected="selected"' : '').' value="'.$a.'">'.($translate ? t($this->_months($a)) : $this->_months($a)) .'</option>'.PHP_EOL;
		}
		$m .= '</select>'.PHP_EOL;
		$d .= '<select name="day'.$name_postfix.'"'.($this->AUTO_ASSIGN_IDS ? ' id="day_'.$name_postfix.'_box"' : '').'>'.PHP_EOL;
		$d .= $show_text ? '<option '.(!$day ? 'selected="selected"' : '').' value="">-'.($translate ? t('day') : 'day').'-</option>'.PHP_EOL : '';
		for ($a = 1; $a <= 31; $a++) {
			$d .= '<option '.(($day == $a) ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$d .= '</select>'.PHP_EOL;
		// Process order
		$tmp_array = array(
			'y'	=> '{%year%}',
			'm'	=> '{%month%}',
			'd'	=> '{%day%}',
		);
		if (empty($show_what)) {
			$show_what = 'ymd';
		}
		$show_what = str_replace(array_keys($tmp_array), array_values($tmp_array), $show_what);
		return str_replace(array_values($tmp_array), array($y,$m,$d), $show_what);
	}

	/**
	*/
	function time_box ($selected = '', $name_postfix = '', $add_str = '', $show_text = 1, $translate = 1) {
		if (is_array($selected)) {
			$extra = $name;
			$selected = $extra['selected'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			$selected = $extra['selected'];
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 1;
			$translate = isset($extra['translate']) ? $extra['translate'] : 1;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		}
		if (strlen($selected))	{
			// Process timestamp (convert it to the 'Y-m-d' pattern)
			if (is_numeric($selected)) {
				$selected = gmdate('H:i:s', $selected);
			}
			list ($hour, $minute, $second) = explode(':', $selected);
		}
		$body .= '<select name="hour'.$name_postfix.'"'.($this->AUTO_ASSIGN_IDS ? ' id="hour_'.$name_postfix.'_box"' : '').'>'.PHP_EOL;
		$body .= $show_text ? '<option '.($hour == '' ? 'selected="selected"' : '').' value="">-'.($translate ? t('hour') : 'hour').'-</option>'.PHP_EOL : '';
		for ($a = 0; $a <= 23; $a++) {
			$body .= '<option '.(($hour == $a && $hour != '') ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$body .= '</select>'.PHP_EOL;
		$body .= '<select name="minute'.$name_postfix.'"'.($this->AUTO_ASSIGN_IDS ? ' id="minute_'.$name_postfix.'_box"' : '').'>'.PHP_EOL;
		$body .= $show_text ? '<option '.($minute == '' ? 'selected="selected"' : '').' value="">-'.($translate ? t('minute') : 'minute').'-</option>'.PHP_EOL : '';
		for ($a = 0; $a <= 59; $a++) {
			$body .= '<option '.(($minute == $a && $minute != '') ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$body .= '</select>'.PHP_EOL;
		$body .= '<select name="second'.$name_postfix.'"'.($this->AUTO_ASSIGN_IDS ? ' id="second_'.$name_postfix.'_box"' : '').'>'.PHP_EOL;
		$body .= $show_text ? '<option '.($second == '' ? 'selected="selected"' : '').' value="">-'.($translate ? t('second') : 'second').'-</option>'.PHP_EOL : '';
		for ($a = 0; $a <= 59; $a++) {
			$body .= '<option '.(($second == $a && $second != '') ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$body .= '</select>'.PHP_EOL;
		return $body;
	}

	/**
	*/
	function date_box2 ($name, $selected = '', $years = '', $add_str = '', $show_what = 'ymd', $show_text = 1, $translate = 1) {
		if (is_array($selected)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			$selected = $extra['selected'];
			$years = isset($extra['years']) ? $extra['years'] : '';
			$show_what = isset($extra['show_what']) ? $extra['show_what'] : 'ymd';
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 1;
			$translate = isset($extra['translate']) ? $extra['translate'] : 1;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		}
		if (strlen($selected))	{
			// Process timestamp (convert it to the 'Y-m-d' pattern)
			if (is_numeric($selected)) {
				$selected = gmdate('Y-m-d', $selected);
			}
			list($year, $month, $day) = explode('-', $selected);
		}
		if (strlen($years)) {
			list($start_year, $end_year) = explode('-', $years);
		} else {
			$start_year = 1900;
			$end_year = gmdate('Y');
		}
		$y .= PHP_EOL.'<select name="'.$name.'[year]"'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_year_box"' : '').'>'.PHP_EOL;
		$y .= $show_text ? '<option '.(!$year ? 'selected="selected"' : '').' value="">-'.($translate ? t('year') : 'year').'-</option>'.PHP_EOL : '';
		for ($a = $start_year; $a <= $end_year; $a++) {
			$y .= '<option '.(($year == $a) ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$y .= '</select>'.PHP_EOL;
		$m .= '<select name="'.$name.'[month]"'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_month_box"' : '').'>'.PHP_EOL;
		$m .= $show_text ? '<option '.(!$month ? 'selected="selected"' : '').' value="">-'.($translate ? t('month') : 'month').'-</option>'.PHP_EOL : '';
		for ($a = 1; $a <= 12; $a++) {
			$m .= '<option '.(($month == $a) ? 'selected="selected"' : '').' value="'.$a.'">'.($translate ? t($this->_months($a)) : $this->_months($a)) .'</option>'.PHP_EOL;
		}
		$m .= '</select>'.PHP_EOL;
		$d .= '<select name="'.$name.'[day]"'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_day_box"' : '').'>'.PHP_EOL;
		$d .= $show_text ? '<option '.(!$day ? 'selected="selected"' : '').' value="">-'.($translate ? t('day') : 'day').'-</option>'.PHP_EOL : '';
		for ($a = 1; $a <= 31; $a++) {
			$d .= '<option '.(($day == $a) ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$d .= '</select>'.PHP_EOL;
		$tmp_array = array(
			'y'	=> '{%year%}',
			'm'	=> '{%month%}',
			'd'	=> '{%day%}',
		);
		if (empty($show_what)) {
			$show_what = 'ymd';
		}
		$show_what = str_replace(array_keys($tmp_array), array_values($tmp_array), $show_what);
		return str_replace(array_values($tmp_array), array($y,$m,$d), $show_what);
	}

	/**
	*/
	function time_box2 ($name, $selected = '', $add_str = '', $show_text = 1, $translate = 1) {
		if (is_array($selected)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			$selected = $extra['selected'];
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 1;
			$translate = isset($extra['translate']) ? $extra['translate'] : 1;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		}
		if (strlen($selected))	{
			// Process timestamp (convert it to the 'Y-m-d' pattern)
			if (is_numeric($selected)) {
				$selected = gmdate('H:i:s', $selected);
			}
			list ($hour, $minute, $second) = explode(':', $selected);
		}
		$body .= PHP_EOL.'<select name="'.$name.'"[hour]'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_hour_box"' : '').'>'.PHP_EOL;
		$body .= $show_text ? '<option '.($hour == "" ? 'selected="selected"' : '').' value="">-'.($translate ? t('hour') : 'hour').'-</option>'.PHP_EOL : '';
		for ($a = 0; $a <= 23; $a++) {
			$body .= '<option '.(($hour == $a && $hour != '') ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$body .= '</select>'.PHP_EOL;
		$body .= '<select name="'.$name.'"[minute]'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_minute_box"' : '').'>'.PHP_EOL;
		$body .= $show_text ? '<option '.($minute == '' ? 'selected="selected"' : '').' value="">-'.($translate ? t('minute') : 'minute').'-</option>'.PHP_EOL : '';
		for ($a = 0; $a <= 59; $a++) {
			$body .= '<option '.(($minute == $a && $minute != '') ? 'selected="selected"' : '').' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$body .= '</select>'.PHP_EOL;
		$body .= '<select name="'.$name.'"[second]'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_second_box"' : '').'>'.PHP_EOL;
		$body .= $show_text ? '<option '.($second == '' ? 'selected="selected"' : '').' value="">-'.($translate ? t('second') : 'second').'-</option>'.PHP_EOL : '';
		for ($a = 0; $a <= 59; $a++) {
			$body .= '<option '.(($second == $a && $second != '') ? 'selected="selected"' : "").' value="'.$a.'">'.$a.'</option>'.PHP_EOL;
		}
		$body .= '</select>'.PHP_EOL;
		return $body;
	}

	/**
	*/
	function datetime_box2 ($name, $selected = '', $years = '', $add_str = '', $show_what = 'ymd', $show_text = 1, $translate = 1) {
		return $this->date_box2($name, $selected, $years, $add_str, $show_what, $show_text, $translate)
			.$this->time_box2($name, $selected, $add_str, $show_text, $translate);
	}

	/**
	* Month name
	*/
	function _months ($num, $lang = '') {
		$m_array = array(
			'January',
			'February',
			'March',
			'April',
			'May',
			'June',
			'July',
			'August',
			'September',
			'October',
			'November',
			'December',
		);
		$num--;
		return (($num > 12) || ($num < 0)) ? $num : $m_array[$num];
	}

	/**
	* Simple input form control
	*/
	function input ($name = '', $value = '', $extra = array()) {
		if (is_array($name)) {
			$extra = $name;
			$name = '';
		}
		if (!is_array($extra)) {
			$extra = array();
		}
		$name = $extra['name'] ? $extra['name'] : 'text';
		$value = $extra['value'] ? $extra['value'] : $value;
		$id = $extra['id'] ? $extra['id'] : $name;
		$desc = $extra['desc'] ? $extra['desc'] : ucfirst(str_replace('_', '', $name));
		$type = $extra['type'] ? $extra['type'] : 'text';

		return '<input type="'.$type.'" name="'.$name.'" id="'.$id.'" value="'.$value.'" placeholder="'.$desc.'"'.($attrs ? ' '.implode(' ', $attrs) : '').'>';
	}

	/**
	*/
	function div_box ($name, $values = array(), $selected = '', $show_text = true, $type = 2, $add_str = '', $translate = 0, $level = 0) {
		// Passing params as array
		if (is_array($name)) {
			$extra = $name;
			$name = $extra['name'];
			$values = isset($extra['values']) ? $extra['values'] : (array)$values; // Required
			if (!$extra['no_translate']) {
				$values = t($values);
			}
			$selected = $extra['selected'];
			$show_text = isset($extra['show_text']) ? $extra['show_text'] : 0;
			$type = isset($extra['type']) ? $extra['type'] : 2;
			$translate = isset($extra['translate']) ? $extra['translate'] : 0;
			$level = isset($extra['level']) ? $extra['level'] : 0;
			$add_str = isset($extra['add_str']) ? $extra['add_str'] : '';
			$extra['class'] .= ' form-control';
			if ($extra['class']) {
				$add_str .= ' class="'.$extra['class'].'" ';
			}
			if ($extra['style']) {
				$add_str .= ' style="'.$extra['style'].'" ';
			}
		} else {
			$add_str .= ' class="form-control" ';
		}
		if (!$values) {
			return false;
		}
		$selected = strval($selected);
/*
		// (example: $add_str = 'size=6')
		$body = $level == 0 ? PHP_EOL.'<select name="'.$name.'"'.($this->AUTO_ASSIGN_IDS ? ' id="'.$name.'_box"' : '').$add_str.">".PHP_EOL : '';
		if ($show_text && $level == 0) {
			$body .= '<option value="">'.($show_text == 1 ? '-'.t('select').' '.t($name).'-' : $show_text).'</option>'.PHP_EOL;
		}
		$self_func = __FUNCTION__;
		foreach ((array)$values as $key => $cur_value) {
			if (is_array($cur_value)) {
				$body .= '<optgroup label="'.$key.'" title="'.($translate ? t($key) : $key).'">'.PHP_EOL;
				$body .= $this->$self_func($name, $cur_value, $selected, $show_text, $type, $add_str, $translate, $level + 1);
				$body .= '</optgroup>'.PHP_EOL;
			} else {
				$_what_compare = strval($type == 1 ? $cur_value : $key);
				$body .= '<option value="'.$key.'" '.($_what_compare == $selected ? 'selected="selected"' : '').'>'.($translate ? t($cur_value) : $cur_value).'</option>'.PHP_EOL;
			}
		}
		$body .= $level == 0 ? '</select>'.PHP_EOL : '';
*/
#		$body .= '<ul class="nav nav-tabs"><li class="dropdown">';
#		$body .= '<ul class="dropdown-menu">';

#		$body .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></a>';
		$body .= '<div class="btn-group">';

		$body .= '<a href="#" class="dropdown-toggle btn" data-toggle="dropdown">Action <span class="caret"></span></a>';
		$body .= '<ul class="dropdown-menu">';
		foreach ((array)$values as $key => $cur_value) {
			$_what_compare = strval($type == 1 ? $cur_value : $key);
			$body .= '<li><a href="javascript:void();" value="'.$key.'" '.($_what_compare == $selected ? 'selected="selected"' : '').'>'.($translate ? t($cur_value) : $cur_value).'</a></li>'.PHP_EOL;
		}
		$body .= '</ul>';

		$body .= '</div>';
#		$body .= '</ul>';
#		$body .= '</li></ul>';

		return $body;
	}
}

<?php

class yf_form2_datetime {

	/**
	* Datetimepicker, src: http://tarruda.github.io/bootstrap-datetimepicker/
	* params :  no_date // no date picker
	*			no_time // no time picker
	*/
	function datetime_select($name = '', $desc = '', $extra = array(), $replace = array(), $__this) {
		if (is_array($desc)) {
			$extra += $desc;
			$desc = '';
		}
		if (!is_array($extra)) {
			$extra = array();
		}
		
		$extra['name'] = $extra['name'] ?: ($name ?: 'date');
		$extra['desc'] = $extra['desc'] ?: ($desc ?: ucfirst(str_replace('_', ' ', $extra['name'])));
		$func = function($extra, $r, $_this) {
			// Compatibility with filter
			if (!strlen($extra['value'])) {
				if (isset($extra['selected'])) {
					$extra['value'] = $extra['selected'];
				} elseif (isset($_this->_params['selected'])) {
					$extra['value'] = $_this->_params['selected'][$extra['name']];
				}
			}
			$format = array();
			if ($extra['no_date']!=1) $format[] = "MM/dd/yyyy";
			if ($extra['no_time']!=1) $format[] = "HH:mm:ss";
			_class('core_js')->add("//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js", true);
			_class('core_js')->add("//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.ru.min.js", true);
			_class('core_css')->add("//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.min.css", true);
			$body = "
<div id=\"{$extra['name']}\" class=\"input-append date date_bd\">
    <input data-format=\"".implode(" ",$format)."\" name=\"{$extra['name']}\" value=\"{$extra['value']}\" type=\"text\" class=\"input-medium\" placeholder=\"{$extra['placeholder']}\"></input>
    <span class=\"add-on\">
		<i class=\"fa fa-calendar\"></i>
    </span>		
</div>
";
			_class('core_js')->add("<script type=\"text/javascript\">
  $(function() {
    $('#{$extra['name']}').datepicker({
      language: 'ru', format: 'dd/mm/yyyy',".($extra['no_time']==1 ? "pickTime: false," : "")."".($extra['no_date']==1 ? "pickDate: false," : "")."
    });
  });
</script>", false);
			return $_this->_row_html($body, $extra, $r);
		};
		if ($__this->_chained_mode) {
			$__this->_body[] = array('func' => $func, 'extra' => $extra, 'replace' => $replace, 'name' => __FUNCTION__);
			return $__this;
		}
		return $func($extra, $replace, $__this);
	}	
}
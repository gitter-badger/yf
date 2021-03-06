<?php

/**
* Translit from RU into EN
*/
class yf_translit {

	/**
	* Make translit from russian or ukrainian text
	*/
	function make ($str) {
		if (empty($str) || !preg_match('/[а-яіїє]+/iu', $str)) {
			return $str;
		}
		if ($this->_is_utf8($str)) {
			$str = iconv("UTF-8", "CP1251//IGNORE", $str);
			//echo strlen($str);
		}
		// ������� �������� "��������������" ������.
		$str = strtr($str,"�������������������������", "abvgdeeziyklmnoprstufhjiei");
		$str = strtr($str,"�����Ũ�����������������ݲ", "ABVGDEEZIYKLMNOPRSTUFHJIEI");
		// ����� - "���������������".
		$str = strtr($str, array(
			"�"=>"zh", "�"=>"ts", "�"=>"ch", "�"=>"sh", 
			"�"=>"shch","�"=>"", "�"=>"yu", "�"=>"ya",
			"�"=>"ZH", "�"=>"TS", "�"=>"CH", "�"=>"SH", 
			"�"=>"SHCH","�"=>"", "�"=>"YU", "�"=>"YA",
			"�"=>"i", "�"=>"Yi", "�"=>"ie", "�"=>"Ye"
		));
/*
		if ($this->_is_utf8($str)) {
return iconv('UTF-8', 'UTF-8//TRANSLIT//IGNORE', $str);
#			$str = iconv('UTF-8', 'CP1251//IGNORE', $str);
#			$str = iconv('CP1251', 'UTF-8//IGNORE', $str);
#			$str = iconv('UTF-8', 'UTF-8//IGNORE', $str);
		}
		// Сначала заменяем 'односимвольные' фонемы.
		$str = strtr($str,'абвгдеёзийклмнопрстуфхъыэі', 'abvgdeeziyklmnoprstufhjiei');
		$str = strtr($str,'АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭІ', 'ABVGDEEZIYKLMNOPRSTUFHJIEI');
		// Затем - 'многосимвольные'.
		$str = strtr($str, array(
			'ж'=>'zh', 'ц'=>'ts', 'ч'=>'ch', 'ш'=>'sh', 
			'щ'=>'shch','ь'=>'', 'ю'=>'yu', 'я'=>'ya',
			'Ж'=>'ZH', 'Ц'=>'TS', 'Ч'=>'CH', 'Ш'=>'SH', 
			'Щ'=>'SHCH','Ь'=>'', 'Ю'=>'YU', 'Я'=>'YA',
			'ї'=>'i', 'Ї'=>'Yi', 'є'=>'ie', 'Є'=>'Ye'
		));
*/
		return $str;
	}

	/**
	* Check if given string is in utf8
	*/
	function _is_utf8($string) {
		return (utf8_encode(utf8_decode($string)) != $string);
	}

	/**
	* 
	*/
	function rus2uni($str,$isTo = true) {
		$arr = array('�'=>'&#x451;','�'=>'&#x401;');
		for($i=192;$i<256;$i++) {
			$arr[chr($i)] = '&#x4'.dechex($i-176).';';
		}
		$str =preg_replace(array('@([�-�]) @i','@ ([�-�])@i'),array('$1&#x0a0;','&#x0a0;$1'),$str);
		return strtr($str,$isTo?$arr:array_flip($arr));
	}

	/**
	* 
	*/
	function utf2win1251 ($s) {
		$out = "";

		for ($i=0; $i<strlen($s); $i++) {
			$c1 = substr ($s, $i, 1);
			$byte1 = ord ($c1);
			if ($byte1>>5 == 6) {// 110x xxxx, 110 prefix for 2 bytes unicode
				$i++;
				$c2 = substr ($s, $i, 1);
				$byte2 = ord ($c2);
				$byte1 &= 31; // remove the 3 bit two bytes prefix
				$byte2 &= 63; // remove the 2 bit trailing byte prefix
				$byte2 |= (($byte1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
				$byte1 >>= 2; // c1 shifts 2 to the right

				$word = ($byte1<<8) + $byte2;
				if ($word==1025) $out .= chr(168);					// ?
				elseif ($word==1105) $out .= chr(184);				// ?
				elseif ($word>=0x0410 && $word<=0x044F) $out .= chr($word-848); // ?-? ?-?
				else {  
					$a = dechex($byte1);
					$a = str_pad($a, 2, "0", STR_PAD_LEFT);
					$b = dechex($byte2);
					$b = str_pad($b, 2, "0", STR_PAD_LEFT);
					$out .= "&#x".$a.$b.";";
				}
			} else {
				$out .= $c1;
			}
		}
		return $out;
	}
}
